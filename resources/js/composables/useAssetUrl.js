export function assetUrl(path) {
    if (!path) {
        return '/images/car-placeholder.svg';
    }

    if (path.startsWith('http')) {
        return path;
    }

    return `/storage/${path}`;
}

