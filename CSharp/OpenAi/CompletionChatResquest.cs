using System.Runtime.Serialization;

namespace Temp.OpenAi;

public class CompletionChatRequest
{
    [DataMember(Name="model")]
    public readonly string Model = "gpt-3.5-turbo";
    
    [DataMember(Name="temperature")]
    public readonly float Temperature = 1f;
    
    [DataMember(Name="max_tokens")]
    public readonly int MaxTokens = 256;
    
    [DataMember(Name="messages")]
    public IEnumerable<CompletionChatMessage>? Messages { get; set; }
}