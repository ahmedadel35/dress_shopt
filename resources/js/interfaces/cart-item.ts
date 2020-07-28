import ProductInterface from './product';
import CartInterface from './cart';
export default interface CartItemInterface {
    id: number;
    cart_id?: number;
    product_id: number;
    instance?: string;
    qty: number;
    size: number;
    color: number;
    price: number;
    sub_total: number;
    product?: ProductInterface;
    cart?: CartInterface;
}