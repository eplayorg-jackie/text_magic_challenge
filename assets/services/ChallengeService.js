import HttpClient from "../utils/HttpClient";

export default class ChallengeService {

    getChallenges(email) {
        return HttpClient.get('/users/${email}/challenges');
    }

    passChallenge(id, email, data) {
        return HttpClient.put('/users/${email}/challenges/${id}');
    }

}
