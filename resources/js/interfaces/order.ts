import UserInterface from './user';
import AddressInterface from './address';
import CartItemInterface from './cart-item';
import ProductInterface from './product';
export default interface OrderInterface {
    id: number;
    orderNum: string;
    user_id: number;
    address_id: number;
    userMail: string;
    status: string | 'pending' | 'processing' | 'completed' | 'decline';
    total: number;
    qty: number;
    paymentStatus: boolean;
    paymentMethod: string | 'accept' | 'onDeliver';
    created_at?: string;
    updated_at?: string;
    owner?: UserInterface;
    items?: CartItemInterface;
    address?: AddressInterface;
    product?: ProductInterface;
}