import ProductInterface from './product';
export default interface UserInterface {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    password: string;
    is_admin: boolean | false;
    img: string;
    image: string;
    products: ProductInterface[];
    created_at: string;
    updated_at: string;
}