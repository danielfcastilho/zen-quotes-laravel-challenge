import Api from "./Api";

class Favorite extends Api {
    async add(userId, quoteId) {
        return await this.axiosInstance({
            method: "post",
            url: `${this.favoriteUrl}`,
            data: {
                user_id: userId,
                quote_id: quoteId,
            },
        });
    }

    async remove(userId, quoteId) {
        return await this.axiosInstance({
            method: "delete",
            url: `${this.favoriteUrl}`,
            data: {
                user_id: userId,
                quote_id: quoteId,
            },
        });
    }
}

const favoriteService = new Favorite();

export default favoriteService;
