import PrimaryButton from "@/Components/PrimaryButton";

export default function QuoteSection({
    auth,
    quote,
    favorite,
    onFavoriteToggle,
}) {
    return (
        <div className="lg:flex-1 p-6 flex flex-col justify-center">
            <blockquote className="italic text-lg md:text-xl lg:text-2xl font-semibold text-gray-700">
                “{quote.quote_text}”
            </blockquote>
            <cite className="block text-right mt-4 text-sm md:text-base lg:text-lg font-medium text-gray-500">
                — {quote.author_name}
            </cite>
            <div className="mt-4 flex justify-center lg:justify-center space-x-4">
                {auth.user && (
                    <PrimaryButton onClick={onFavoriteToggle}>
                        {favorite ? "Remove favorite" : "Add to favorites"}
                    </PrimaryButton>
                )}
            </div>
        </div>
    );
}
