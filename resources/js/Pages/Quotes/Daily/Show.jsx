import DefaultLayout from "@/Layouts/DefaultLayout";
import { Head } from "@inertiajs/react";
import { useState } from "react";
import QuoteSection from "@/Components/QuoteSection";
import ImageSection from "@/Components/ImageSection";

export default function Show({
    auth,
    quote,
    randomInspirationalImagePath,
    isFavorite,
}) {
    return (
        <DefaultLayout
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Quote of the Day
                </h2>
            }
            forceReloadUrl="/today/new"
        >
            <Head title="Quote of the Day" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col lg:flex-row items-center">
                        <QuoteSection
                            auth={auth}
                            quote={quote}
                            isFavorite={isFavorite}
                        />
                        <ImageSection
                            imagePath={randomInspirationalImagePath}
                        />
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}
