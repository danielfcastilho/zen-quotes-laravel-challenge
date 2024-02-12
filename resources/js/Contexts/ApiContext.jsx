import React, { createContext, useContext, useState, useEffect } from "react";
import { usePage } from "@inertiajs/react";
import FavoriteService from "@/Services/FavoriteService";

const ApiContext = createContext(null);

export const ApiProvider = ({ children }) => {
    const { props } = usePage();
    const [apiServices, setApiServices] = useState({
        favoriteService: null,
    });
    
    useEffect(() => {
        if (props.apiToken) {
            const favoriteServiceInstance = new FavoriteService(props.apiToken);
            setApiServices({
                favoriteService: favoriteServiceInstance,
            });
        }
    }, [props.apiToken]);

    return (
        <ApiContext.Provider value={apiServices}>{children}</ApiContext.Provider>
    );
};

export const useApi = () => useContext(ApiContext);
