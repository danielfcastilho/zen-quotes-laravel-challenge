import QuoteSection from "@/Components/QuoteSection";
import DefaultLayout from "@/Layouts/DefaultLayout";
import { Head } from "@inertiajs/react";

export default function SecureList({ auth, quotes, favorites }) {
    return (
        <DefaultLayout
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Ten Secure Quotes
                </h2>
            }
            forceReloadUrl="/secure-quotes/new"
        >
            <Head title="Ten Secure Quotes" />
            <div className="py-12">
                {quotes.map((quote) => (
                    <div key={quote.id} className="mb-5 max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6 text-gray-900">
                                <QuoteSection
                                    auth={auth}
                                    quote={quote}
                                    isFavorite={favorites.includes(quote.id)}
                                />
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </DefaultLayout>
    );
}
