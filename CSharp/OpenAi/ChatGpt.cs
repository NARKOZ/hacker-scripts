using System.Net.Http.Headers;
using System.Text;
using System.Text.Json;

namespace Temp.OpenAi;

public sealed class ChatGpt
{
    private readonly HttpClient _httpClient;
    private const string OpenAiSecret = "your secret api";
    private const string ApplicationJsonMediaTypeRequest = "application/json";
    private const string AcceptHeaderRequest = "Accept";
    private const string OpenAiApiBaseUrl = "https://api.openai.com/v1/";
    private readonly JsonSerializerOptions _serializerOptions = new()
    {
        PropertyNameCaseInsensitive = true
    };
    public ChatGpt()
    {
        _httpClient = new HttpClient();
        _httpClient.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Bearer", OpenAiSecret);
        _httpClient.BaseAddress = new Uri(OpenAiApiBaseUrl);
        _httpClient.DefaultRequestHeaders.Add(AcceptHeaderRequest, ApplicationJsonMediaTypeRequest);
    }
    
    public async Task<IEnumerable<string>?> GetReasonsToMyBitch()
    {
        const string prompt = "Return only a CSV list separated by semicolons, of phrases with various reasons that justify " +
                              "my delay in leaving work, to my wife. Do not repeat this question in your response. " +
                              "Only the raw CSV. No double quotes. Just raw CSV";

        return await DoRequest(prompt);
    }
    
    public async Task<IEnumerable<string>?> GetExcusesToMyMates()
    {
        const string prompt = "Return only a CSV list separated by semicolons, of phrases with various reasons that " +
                              "justify why I can't go out for a drink with my friends. Do not repeat this question in " +
                              "your response. Only the raw CSV. No double quotes. Just raw CSV";

        return await DoRequest(prompt);
    }

    private async Task<IEnumerable<string>?> DoRequest(string prompt)
    {
        var promptJson = new CompletionChatRequest
        {
            Messages = new List<CompletionChatMessage>
            {
                new() { Content = prompt }
            }
        };
        
        var content = new StringContent(JsonSerializer.Serialize(promptJson), Encoding.UTF8, ApplicationJsonMediaTypeRequest);
        var responseMessage = 
            await _httpClient.PostAsync("chat/completions", content).ConfigureAwait(false);
        
        var responseContent = await responseMessage.Content.ReadAsStringAsync().ConfigureAwait(false);

        var response = JsonSerializer.Deserialize<CompletionChatResponse>(responseContent, _serializerOptions);
        return response?.Content?.Split(";");
    }
}