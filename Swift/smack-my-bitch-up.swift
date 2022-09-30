import Foundation

let accountSID = "TWILLIO_ACCOUNT_SID"
let authToken = "TWILLIO_AUTH_TOKEN"

let myNumber = "+XXX"
let herNumber = "+XXX"

func Smackmybitch(){
    let url = "https://api.twilio.com/2010-04-01/Accounts/\(accountSID)/Messages"
    let parameters = ["From": "\(myNumber)", "To": "\(herNumber)", "Body": "\(message)"]
    let auth = ["user": accountSID, "password": authToken]


    var request = URLRequest(url: url)
    request.setValue("Application/json", forHTTPHeaderField: "Content-Type")
    request.httpMethod = "POST"
    guard let httpBody = try? JSONSerialization.data(withJSONObject: parameters, options: []) else {
            return
        }
    request.httpBody = httpBody
    var authEncoded = "\(auth[user]!):\(auth[password]!)".dataUsingEncoding(NSASCIIStringEncoding, allowLossyConversion: true)!.base64EncodedStringWithOptions(NSDataBase64EncodingOptions.allZeros);
    var authValue = "Basic \(authEncoded)"
    urlRequest.setValue(authValue, forHTTPHeaderField: "Authorization")

    let session = NSURLSession(configuration: config)
    session.dataTaskWithRequest(request) { (data, response, error) -> Void in }.resume()
}

let reasons = [
    "Working Hard",
    "Gotta ship this feature",
    "Somebody fucked the system again."
]

let random = Int(arc4random_uniform(2))
let randomReason = reasons[random]
let message = "Late at work. \(randomReason)"
Smackmybitch()