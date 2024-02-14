import ApiService from "./ApiService";

class FavoriteQuoteService extends ApiService {
    constructor(token) {
        super(token);
    }

    async add(quoteId) {
        return await this.axiosInstance({
            method: "post",
            url: `${this.favoriteQuotesUrl}`,
            data: {
                quote_id: quoteId,
                action: "add",
            },
        });
    }

    async remove(quoteId) {
        return await this.axiosInstance({
            method: "post",
            url: `${this.favoriteQuotesUrl}`,
            data: {
                quote_id: quoteId,
                action: "remove",
            },
        });
    }
}

export default FavoriteQuoteService;
