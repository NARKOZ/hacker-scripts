namespace Temp.OpenAi;

public record CompletionChatResponse
{
    public CompletionChatChoice[] Choices { get; set; }
    public string? Content => Choices.FirstOrDefault().Message.Content;
}