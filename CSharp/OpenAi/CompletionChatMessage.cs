using System.Runtime.Serialization;

namespace Temp.OpenAi;

public record struct CompletionChatMessage()
{
    [DataMember(Name="role")]
    public readonly string Role = "user";
    
    [DataMember(Name="content")]
    public string? Content { get; set; } = string.Empty;
}
