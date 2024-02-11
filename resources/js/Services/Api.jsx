import axios from "axios";

export default class Api {
    constructor() {
        const apiURL = "/api";
        this.authUrl = `${apiURL}/auth`;
        this.favoriteUrl = `${apiURL}/favorite`;

        this.axiosInstance = axios.create();

        // Add a request interceptor
        this.axiosInstance.interceptors.request.use(
            (config) => {
                const token = localStorage.getItem("jwtToken");

                config.headers["Content-Type"] = "application/json";
                config.withCredentials = true;

                if (token) {
                    config.headers["Authorization"] = "Bearer " + token;
                }

                return config;
            },
            (error) => {
                return Promise.reject(error);
            }
        );

        // Add a response interceptor
        this.axiosInstance.interceptors.response.use(
            (response) => {
                if (response.data.message) {
                    //success notification
                }

                return response;
            },
            (error) => {
                if (error.response) {
                    switch (error.response.status) {
                        case 400:
                        case 401:
                        case 403:
                        case 404:
                            if (error.response.data.message) {
                                //error notification
                            }
                            break;
                        case 422:
                            if (error.response.data.errors) {
                                //error notification
                            }
                            break;
                        case 500:
                        default:
                        //error notification
                    }
                } else if (error.request) {
                    console.error("No response received:", error.request);
                } else {
                    console.error("Error", error.message);
                }
                return Promise.reject(error);
            }
        );
    }
}
