import axios from "axios";

export default class ApiService {
    constructor(token) {
        const apiURL = "/api";

        this.token = token;

        this.randomQuotesUrl = `${apiURL}/quotes`;
        this.secureQuotesUrl = `${apiURL}/secure-quotes`;
        this.favoriteQuotesUrl = `${apiURL}/favorite-quotes`;

        this.axiosInstance = axios.create();

        // Add a request interceptor
        this.axiosInstance.interceptors.request.use(
            (config) => {
                config.headers["Content-Type"] = "application/json";
                config.withCredentials = true;

                if (this.token) {
                    config.headers["Authorization"] = "Bearer " + this.token;
                }

                return config;
            },
            (error) => {
                return Promise.reject(error);
            }
        );
    }

    setApiToken(token = null) {
        this.token = token;
    }
}
