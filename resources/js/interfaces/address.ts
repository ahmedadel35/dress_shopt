export default interface AddressInterface {
    id?: number;
    userMail: string;
    firstName: string;
    lastName: string;
    address: string;
    dep: string;
    city: string;
    country: string;
    gov: string;
    postCode: string;
    phoneNumber: string;
    notes: string;
    created_at?: string;
    updated_at?: string;
}
