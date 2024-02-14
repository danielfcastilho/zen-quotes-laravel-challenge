import ApiService from "./ApiService";

class RandomQuoteService extends ApiService {
    constructor(token) {
        super(token);
    }

    async index(forceReload = null) {
        return await this.axiosInstance({
            method: "get",
            url: `${this.randomQuotesUrl}${forceReload ? "/new" : ""}`,
        });
    }
}

export default RandomQuoteService;
