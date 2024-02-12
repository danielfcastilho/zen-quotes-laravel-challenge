import ApiService from "./ApiService";

class FavoriteService extends ApiService {
    constructor(token) {
        super(token);
    }

    async add(quoteId) {
        return await this.axiosInstance({
            method: "post",
            url: `${this.favoriteUrl}`,
            data: {
                quote_id: quoteId,
            },
        });
    }

    async remove(quoteId) {
        return await this.axiosInstance({
            method: "delete",
            url: `${this.favoriteUrl}`,
            data: {
                quote_id: quoteId,
            },
        });
    }
}

export default FavoriteService;
