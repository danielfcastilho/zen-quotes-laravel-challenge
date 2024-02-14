import React from "react";
import { Head, usePage } from "@inertiajs/react";
import DefaultLayout from "@/Layouts/DefaultLayout";
import GetQuotes from "./Partials/GetQuotes";
import GetSecureQuotes from "./Partials/GetSecureQuotes";
import PostFavoriteQuotes from "./Partials/PostFavoriteQuotes";

export default function ApiTest({ auth }) {
    const { props } = usePage();
    const currentApiToken = props.apiToken;

    return (
        <DefaultLayout
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Online API Test
                </h2>
            }
        >
            <Head title="Online API Test" />
            <div className="py-12">
                <GetQuotes auth={auth} currentApiToken={currentApiToken} />
                <GetSecureQuotes
                    auth={auth}
                    currentApiToken={currentApiToken}
                />
                <PostFavoriteQuotes
                    auth={auth}
                    currentApiToken={currentApiToken}
                />
            </div>
        </DefaultLayout>
    );
}
