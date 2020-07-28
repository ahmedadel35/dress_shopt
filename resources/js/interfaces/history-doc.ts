export interface Param {
    key: string;
    info: string;
    req: boolean;
    default?: string;
}

export default interface HistoryDocInterface {
    route: string;
    method: string | "GET" | "POST";
    info: string;
    url_with_params: string;
    test_curl: string;
    response: string;
    headers: Param[];
    res_doc: [number, string];
    url_params: Param[];
    query: Param[];
    parent: string;
}
