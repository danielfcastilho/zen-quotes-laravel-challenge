export default function Quote({quote}) {
    return (
        <div className="lg:w-1/2 p-6">
            <blockquote className="italic text-lg md:text-xl lg:text-2xl font-semibold text-gray-700">
                “{quote.quote_text}”
            </blockquote>
            <cite className="block text-right mt-4 text-sm md:text-base lg:text-lg font-medium text-gray-500">
                — {quote.author_name}
            </cite>
        </div>
    );
}
