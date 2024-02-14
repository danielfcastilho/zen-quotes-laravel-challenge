import ApiService from "./ApiService";

class SecureQuoteService extends ApiService {
    constructor(token) {
        super(token);
    }

    async index(forceReload = false) {
        return await this.axiosInstance({
            method: "get",
            url: `${this.secureQuotesUrl}${forceReload ? "/new" : ""}`,
        });
    }
}

export default SecureQuoteService;
