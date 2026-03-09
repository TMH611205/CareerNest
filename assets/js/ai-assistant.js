import { GoogleGenAI } from "@google/genai";

// Configuration
const GEMINI_API_KEY = process.env.GEMINI_API_KEY;
let genAI = null;
let chat = null;
const modelName = "gemini-3-flash-preview";

const initAI = () => {
  if (chat) return chat;
  
  if (!GEMINI_API_KEY || GEMINI_API_KEY === 'undefined') {
    console.error("CareerNest AI: GEMINI_API_KEY is missing or undefined.");
    return null;
  }

  try {
    genAI = new GoogleGenAI({ apiKey: GEMINI_API_KEY });
    chat = genAI.chats.create({
      model: modelName,
      config: {
        systemInstruction: `Bạn là trợ lý AI của CareerNest. 
PHONG CÁCH: Thân thiện, lịch sự, tích cực.
QUY TẮC TRẢ LỜI (BẮT BUỘC):
1. CỰC KỲ NGẮN GỌN: Trả lời trong tối đa 2 câu ngắn. Không giải thích dài dòng, không dẫn nhập rườm rà.
2. ĐÚNG TRỌNG TÂM: Đi thẳng vào câu trả lời cho câu hỏi của người dùng.
3. KHÔNG THỪA THÃI: Không chào hỏi lại ở mỗi câu trả lời, không hỏi "Tôi có thể giúp gì thêm không?" trừ khi thực sự cần thiết.
4. NGÔN NGỮ: Tiếng Việt tự nhiên, chuyên nghiệp nhưng gần gũi.`,
      },
    });
    return chat;
  } catch (err) {
    console.error("CareerNest AI Initialization Error:", err);
    return null;
  }
};

// CSS Injection
const style = document.createElement('style');
style.textContent = `
  .ai-chat-widget {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 9999;
    font-family: 'Inter', sans-serif;
  }

  .ai-chat-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background-color: #0047AB;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0, 71, 171, 0.3);
    transition: all 0.3s ease;
    border: none;
  }

  .ai-chat-button:hover {
    transform: scale(1.1);
    background-color: #F27D26;
  }

  .ai-chat-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 350px;
    height: 500px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    display: none;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid #e2e8f0;
  }

  .ai-chat-window.active {
    display: flex;
  }

  .ai-chat-header {
    background-color: #0047AB;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .ai-chat-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
  }

  .ai-chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background-color: #f8fafc;
  }

  .ai-message {
    max-width: 85%;
    padding: 10px 14px;
    border-radius: 15px;
    font-size: 14px;
    line-height: 1.4;
  }

  .ai-message.user {
    align-self: flex-end;
    background-color: #0047AB;
    color: white;
    border-bottom-right-radius: 2px;
  }

  .ai-message.bot {
    align-self: flex-start;
    background-color: white;
    color: #1e293b;
    border-bottom-left-radius: 2px;
    border: 1px solid #e2e8f0;
  }

  .ai-chat-input-area {
    padding: 15px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 10px;
    background: white;
  }

  .ai-chat-input {
    flex: 1;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
  }

  .ai-chat-input:focus {
    border-color: #0047AB;
  }

  .ai-chat-send {
    background: #0047AB;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 8px 15px;
    cursor: pointer;
  }

  .ai-typing {
    font-style: italic;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 4px;
  }

  .ai-typing::after {
    content: '';
    width: 4px;
    height: 4px;
    background: #64748b;
    border-radius: 50%;
    animation: ai-blink 1s infinite;
  }

  @keyframes ai-blink {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 1; }
  }
`;
document.head.appendChild(style);

// HTML Injection
const widgetContainer = document.createElement('div');
widgetContainer.className = 'ai-chat-widget';
widgetContainer.innerHTML = `
  <button class="ai-chat-button" id="ai-chat-toggle">
    <i data-lucide="message-circle"></i>
  </button>
  <div class="ai-chat-window" id="ai-chat-window">
    <div class="ai-chat-header">
      <h3>CareerNest AI Assistant</h3>
      <button id="ai-chat-close" style="color: white; cursor: pointer;"><i data-lucide="x"></i></button>
    </div>
    <div class="ai-chat-messages" id="ai-chat-messages">
      <div class="ai-message bot">Chào bạn! Mình là trợ lý CareerNest. Bạn cần tư vấn gì về ngành học hay nghề nghiệp không?</div>
    </div>
    <div class="ai-chat-input-area">
      <input type="text" class="ai-chat-input" id="ai-chat-input" placeholder="Nhập câu hỏi của bạn...">
      <button class="ai-chat-send" id="ai-chat-send">Gửi</button>
    </div>
  </div>
`;
document.body.appendChild(widgetContainer);

// Chat Logic
const toggleBtn = document.getElementById('ai-chat-toggle');
const closeBtn = document.getElementById('ai-chat-close');
const chatWindow = document.getElementById('ai-chat-window');
const chatInput = document.getElementById('ai-chat-input');
const sendBtn = document.getElementById('ai-chat-send');
const messagesContainer = document.getElementById('ai-chat-messages');

toggleBtn.addEventListener('click', () => {
  chatWindow.classList.toggle('active');
  if (chatWindow.classList.contains('active')) {
    chatInput.focus();
  }
});

closeBtn.addEventListener('click', () => {
  chatWindow.classList.remove('active');
});

const addMessage = (text, sender) => {
  const msgDiv = document.createElement('div');
  msgDiv.className = `ai-message ${sender}`;
  msgDiv.textContent = text;
  messagesContainer.appendChild(msgDiv);
  messagesContainer.scrollTop = messagesContainer.scrollHeight;
  return msgDiv;
};

const handleSendMessage = async (overrideText) => {
  const text = typeof overrideText === 'string' ? overrideText : chatInput.value.trim();
  if (!text) return;

  addMessage(text, 'user');
  chatInput.value = '';

  // Add typing indicator
  const typingIndicator = document.createElement('div');
  typingIndicator.className = 'ai-typing';
  typingIndicator.textContent = 'AI đang xử lý...';
  messagesContainer.appendChild(typingIndicator);
  messagesContainer.scrollTop = messagesContainer.scrollHeight;

  const currentChat = initAI();
  if (!currentChat) {
    typingIndicator.remove();
    addMessage("Rất tiếc, hệ thống AI chưa được cấu hình đúng. Vui lòng liên hệ quản trị viên.", 'bot');
    return;
  }

  try {
    const responseStream = await currentChat.sendMessageStream({ message: text });
    typingIndicator.remove();
    
    const botMsgDiv = document.createElement('div');
    botMsgDiv.className = 'ai-message bot';
    messagesContainer.appendChild(botMsgDiv);
    
    let fullText = '';
    for await (const chunk of responseStream) {
      const chunkText = chunk.text;
      if (chunkText) {
        fullText += chunkText;
        botMsgDiv.textContent = fullText;
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
      }
    }
  } catch (error) {
    console.error("AI Error:", error);
    if (typingIndicator.parentNode) typingIndicator.remove();
    addMessage("Rất tiếc, đã có lỗi xảy ra. Vui lòng thử lại sau.", 'bot');
  }
};

sendBtn.addEventListener('click', handleSendMessage);
chatInput.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') handleSendMessage();
});

// Re-initialize Lucide icons for the injected elements
if (window.lucide) {
  window.lucide.createIcons();
}

// Global function to allow other scripts to interact with AI
window.askCareerAI = async (prompt) => {
  if (!chatWindow.classList.contains('active')) {
    chatWindow.classList.add('active');
  }
  await handleSendMessage(prompt);
};
