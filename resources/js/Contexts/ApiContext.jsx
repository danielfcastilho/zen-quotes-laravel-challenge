import React, { createContext, useContext, useState, useEffect } from "react";
import { usePage } from "@inertiajs/react";
import RandomQuoteService from "@/Services/RandomQuoteService";
import SecureQuoteService from "@/Services/SecureQuoteService";
import FavoriteQuoteService from "@/Services/FavoriteQuoteService";

const ApiContext = createContext(null);

export const ApiProvider = ({ children }) => {
    const { props } = usePage();
    const [apiServices, setApiServices] = useState({
        randomQuoteService: null,
        secureQuoteService: null,
        favoriteQuoteService: null,
    });

    useEffect(() => {
        const randomQuoteService = new RandomQuoteService(props.apiToken);
        const secureQuoteService = new SecureQuoteService(props.apiToken);
        const favoriteQuoteServiceInstance = new FavoriteQuoteService(
            props.apiToken
        );
        setApiServices({
            randomQuoteService: randomQuoteService,
            secureQuoteService: secureQuoteService,
            favoriteQuoteService: favoriteQuoteServiceInstance,
        });
    }, [props.apiToken]);

    return (
        <ApiContext.Provider value={apiServices}>
            {children}
        </ApiContext.Provider>
    );
};

export const useApi = () => useContext(ApiContext);
