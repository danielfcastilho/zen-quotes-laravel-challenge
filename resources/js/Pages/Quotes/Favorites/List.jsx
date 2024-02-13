import QuoteSection from "@/Components/QuoteSection";
import DefaultLayout from "@/Layouts/DefaultLayout";
import { Head } from "@inertiajs/react";

export default function List({ auth, quotes }) {
    return (
        <DefaultLayout
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Favorite Quotes
                </h2>
            }
        >
            <Head title="Favorite Quotes" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {quotes.length > 0 ? (
                        <div className="grid md:grid-cols-2 gap-4">
                            {quotes.map((quote) => (
                                <div
                                    key={quote.id}
                                    className="bg-white overflow-hidden shadow-sm sm:rounded-lg"
                                >
                                    <div className="p-6 text-gray-900">
                                        <QuoteSection
                                            auth={auth}
                                            quote={quote}
                                            isFavorite={true}
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="text-center px-6 py-4">
                            <p className="text-lg text-gray-600">
                                You don't have any favorite quotes yet.
                            </p>
                            <p className="mt-2 text-gray-600">
                                Browse quotes and click "Add to favorites" to start
                                building your collection!
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </DefaultLayout>
    );
}
