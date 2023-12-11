import { api } from "src/boot/axios";

class GenerateListService {
  static async getData(params) {
    return (await api.get("api/generatelist", {params:params})).data;
  }
}

export default GenerateListService;
