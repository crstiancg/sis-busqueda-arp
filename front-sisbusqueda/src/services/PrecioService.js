import { api } from "src/boot/axios";

class PrecioService {
    static async getData(params) {
        return (await api.get('/api/precios',params)).data;
    }

    static async get(id) {
        return (await api.get(`/api/precios/${id}`)).data;
    }

    static async delete(id) {
        return (await api.delete(`/api/precios/${id}`));
    }

    static async save(reg) {
        if (reg.id === undefined || reg.id === null) {
            return (await api.post("/api/precios", reg)).data;
        } else {
            return (await api.put(`/api/precios/${reg.id}`, reg)).data;
        }
    }
}

export default PrecioService;
