export default function ImageSection({ imagePath }) {
    return (
        <div className="lg:w-auto flex justify-center lg:justify-end p-6">
            <img
                src={imagePath}
                alt="Inspirational"
                className="object-contain shadow-lg rounded-lg"
                style={{ maxWidth: "100%", maxHeight: "400px" }}
            />
        </div>
    );
}
