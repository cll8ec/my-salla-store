/**
 * Salla API integration JS helpers.
 */
export async function fetchSallaProducts() {
    try {
        const response = await fetch('/api/products');
        return await response.json();
    } catch (error) {
        console.error('Error fetching Salla products:', error);
        return { success: false, data: [] };
    }
}
