<?php

namespace Tests\Feature;

use App\Category;
use App\Http\Controllers\Admin\ProductController;
use App\Product;
use App\Rate;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\CategoryFactory;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testProductsPageShowingAllCatsList()
    {
        $this->withoutExceptionHandling();
        $c = factory(Category::class)->create();
        $sub_cats = $c->sub_cats()->saveMany(
            factory(Category::class, 5)->make()
        );

        $this->get('/en/products/' . $sub_cats->first()->slug)
            ->assertOk()
            ->assertSee($sub_cats->first()->title)
            ->assertSee($sub_cats->last()->title);
    }

    public function testAnyOneCanLoadProductsPageBasedOnCategory()
    {
        [, $sub, $p,,] = CategoryFactory::wSub()->wPro(7)->create();

        $this->getJson('/collection/' . $sub->slug)
            ->assertOk()
            ->assertJsonCount(7, 'data')
            ->assertSee($p->slug);
    }

    public function testLoadingProductDataWithSlugWithoutRates()
    {
        [,, $p,,] = CategoryFactory::wSub()->wPro(7)->create();

        $p->makeHidden('rate_avg');

        $this->getJson('/product/' . $p->slug)
            ->assertOk()
            ->assertJson($p->toArray());
    }

    // TODO test filters
    // TODO test sort data by

    public function testAnyOneCanVisitProductPage()
    {
        $p = factory(Product::class)->create();
        // $tags = $p->tags

        $this->get('/en/product/' . $p->slug)
            ->assertOk()
            ->assertSee($p->title);
    }

    public function testOnlyAdminCanAccessProductCreate()
    {
        $this->signIn();

        $this->post('/product', [])
            ->assertForbidden();
    }

    public function testAdminCannotCreateProductWithInvalidData()
    {
        // $this->withoutExceptionHandling();
        $this->signIn(null, [
            'role' => User::AdminRole
        ]);

        $this->post('/product', [])
            ->assertRedirect()
            ->assertSessionHasErrors();
    }

    public function testCreatingProductWillUploadAllImages()
    {
        $this->withoutExceptionHandling();
        $this->signIn(null, ['role' => User::AdminRole]);
        $product = factory(Product::class)->raw();
        Storage::fake('local');

        $img = UploadedFile::fake()->image('asd.png');
        $img2 = UploadedFile::fake()->image('acsdg.jpg');
        $product['colors'] = ['red', 'blue', 'green'];
        $product['sizes'] = ['M', 'ML', 'S'];
        $product['category_slug'] = (factory(Category::class)->create())->slug;
        $product['images'] = [$img, $img2];

        $res = $this->postJson('/product', $product)
            ->assertOk()
            ->assertJsonPath('image', url(
                ProductController::UPLOAD_PATH . '/' . $img->hashName()
            ));

        // dd($res->dump()->json());

        Storage::disk('local')->assertExists(
            ProductController::UPLOAD_PATH . '/' . $img->hashName()
        );

        $this->assertDatabaseHas('products', [
            'title' => $product['title'],
            'category_slug' => $product['category_slug']
        ]);
    }

    public function testUpdatingProductWillImages()
    {
        $this->withoutExceptionHandling();
        $this->signIn(null, ['role' => User::AdminRole]);
        $product = factory(Product::class)->raw();
        Storage::fake('local');

        $img = UploadedFile::fake()->image('asd.png');
        $img2 = UploadedFile::fake()->image('acsdg.jpg');
        $img3 = UploadedFile::fake()->image('wxdsd.jpg');
        $img4 = UploadedFile::fake()->image('qqqsd.jpg');
        $title = $this->faker->sentence;
        $product['title'] = $title;
        $product['colors'] = 'red,blue,green';
        $product['sizes'] = 'M,ML,S';
        $product['category_slug'] = (factory(Category::class)->create())->slug;
        $product['images'] = [$img, $img2];

        $res = $this->postJson('/product', $product)
            ->assertJsonPath('image', url(
                ProductController::UPLOAD_PATH . '/' . $img->hashName()
            ));

        $p = (object) $res->json();

        $product['images'] = [$img3, $img4];
        $this->patchJson('/product/' . $p->slug, $product)
            ->assertOk()
            ->assertJsonPath('image', url(
                ProductController::UPLOAD_PATH . '/' . $img3->hashName()
            ));

        $this->assertDatabaseHas('products', [
            'slug' => $p->slug,
            'title' => $title
        ]);

        // assert new images saved
        Storage::disk('local')->assertExists(
            ProductController::UPLOAD_PATH . '/' . $img3->hashName()
        );

        // assert old images deleted
        Storage::disk('local')->assertMissing(
            ProductController::UPLOAD_PATH . '/' . $img->hashName()
        );
    }
}
