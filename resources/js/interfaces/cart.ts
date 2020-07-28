import CartItemInterface from './cart-item';
import UserInterface from './user';
export default interface CartInterface {
    id?: number;
    user_id?: number;
    instance?: string;
    created_at?: string;
    updated_at?: string;
    items: CartItemInterface[];
    wish: CartItemInterface[];
    owner?: UserInterface;
    total: number;
    count: number;
    loaders?: number[];
}