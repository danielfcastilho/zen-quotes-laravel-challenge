import PrimaryButton from "@/Components/PrimaryButton";
import { useState } from "react";
import { useApi } from "@/Contexts/ApiContext";

export default function QuoteSection({ auth, quote, onFavoriteRemoved = null }) {
    const [favorite, setFavorite] = useState(quote.is_favorite);

    const { favoriteQuoteService } = useApi();

    const handleFavoriteToggle = async () => {
        if (!favorite) {
            await favoriteQuoteService.add(quote.id);
        } else {
            await favoriteQuoteService.remove(quote.id);
            onFavoriteRemoved && onFavoriteRemoved();
        }
        setFavorite(!favorite);
    };

    return (
        <div className="lg:flex-1 p-6 flex flex-col justify-center">
            <blockquote className="italic text-lg md:text-xl lg:text-2xl font-semibold text-gray-700">
                “{quote.quote_text}”
            </blockquote>
            <cite className="block text-right mt-4 text-sm md:text-base lg:text-lg font-medium text-gray-500">
                — {quote.author_name}
            </cite>
            <div className="mt-4 flex justify-center lg:justify-center space-x-4">
                {auth && auth.user && (
                    <PrimaryButton onClick={handleFavoriteToggle}>
                        {favorite ? "Remove favorite" : "Add to favorites"}
                    </PrimaryButton>
                )}
            </div>
        </div>
    );
}
