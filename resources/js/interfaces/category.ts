import ProductInterface from './product';
export default interface CategoryInterface {
    id: number;
    title: string;
    slug?: string;
    category_id: number;
    products: ProductInterface[];
    created_at: string;
    updated_at: string;
}