import HttpClient from "../utils/HttpClient";

class SessionService {

    async getSession(email) {
        return await HttpClient.get('/users/${email}');
    }

    async activateSession(email) {
        return await HttpClient.post('/users/${email}');
    }

}

export default new SessionService();