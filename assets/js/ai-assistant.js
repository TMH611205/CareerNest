import { GoogleGenAI } from "@google/genai";

// Initialize Gemini API
const ai = new GoogleGenAI({ apiKey: process.env.GEMINI_API_KEY });

/**
 * Global function to ask the AI about career advice
 * @param {string} prompt - The user's question or context
 */
window.askCareerAI = async function(prompt) {
  // Create a modal or overlay for the AI response if it doesn't exist
  let modal = document.getElementById('ai-assistant-modal');
  if (!modal) {
    modal = document.createElement('div');
    modal.id = 'ai-assistant-modal';
    modal.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.8);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      backdrop-filter: blur(8px);
    `;
    modal.innerHTML = `
      <div style="background: white; width: 90%; max-width: 600px; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px rgba(0,0,0,0.5); display: flex; flex-direction: column; max-height: 80vh;">
        <div style="padding: 24px; background: var(--color-brand-blue); color: white; display: flex; justify-content: space-between; align-items: center;">
          <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
              <i data-lucide="sparkles" style="width: 18px;"></i>
            </div>
            <h3 style="margin: 0; font-size: 18px;">Trợ Lý CareerNest AI</h3>
          </div>
          <button id="close-ai-modal" style="background: none; border: none; color: white; cursor: pointer; opacity: 0.7; transition: opacity 0.2s;">
            <i data-lucide="x"></i>
          </button>
        </div>
        <div id="ai-response-content" style="padding: 32px; overflow-y: auto; line-height: 1.6; color: var(--color-slate-700); font-size: 16px;">
          <div class="ai-loading">
            <div style="display: flex; gap: 8px; margin-bottom: 16px;">
              <div style="width: 8px; height: 8px; background: var(--color-brand-orange); border-radius: 50%; animation: bounce 0.6s infinite alternate;"></div>
              <div style="width: 8px; height: 8px; background: var(--color-brand-orange); border-radius: 50%; animation: bounce 0.6s infinite alternate 0.2s;"></div>
              <div style="width: 8px; height: 8px; background: var(--color-brand-orange); border-radius: 50%; animation: bounce 0.6s infinite alternate 0.4s;"></div>
            </div>
            <p>Đang phân tích yêu cầu và chuẩn bị lời khuyên cho bạn...</p>
          </div>
        </div>
        <div style="padding: 16px 24px; background: var(--color-slate-50); border-top: 1px solid var(--color-slate-100); text-align: center; font-size: 12px; color: var(--color-slate-400);">
          Lời khuyên từ AI chỉ mang tính chất tham khảo. Hãy kết hợp với tư vấn từ chuyên gia thực tế.
        </div>
      </div>
      <style>
        @keyframes bounce {
          from { transform: translateY(0); }
          to { transform: translateY(-8px); }
        }
        #ai-response-content h4 { color: var(--color-brand-blue); margin-top: 0; }
        #ai-response-content p { margin-bottom: 16px; }
        #ai-response-content ul { padding-left: 20px; margin-bottom: 16px; }
        #ai-response-content li { margin-bottom: 8px; }
      </style>
    `;
    document.body.appendChild(modal);
    
    document.getElementById('close-ai-modal').addEventListener('click', () => {
      modal.style.display = 'none';
    });

    // Re-run lucide icons for the new elements
    if (window.lucide) window.lucide.createIcons();
  }

  // Show modal and reset content
  modal.style.display = 'flex';
  const contentArea = document.getElementById('ai-response-content');
  contentArea.innerHTML = `
    <div class="ai-loading">
      <div style="display: flex; gap: 8px; margin-bottom: 16px;">
        <div style="width: 8px; height: 8px; background: var(--color-brand-orange); border-radius: 50%; animation: bounce 0.6s infinite alternate;"></div>
        <div style="width: 8px; height: 8px; background: var(--color-brand-orange); border-radius: 50%; animation: bounce 0.6s infinite alternate 0.2s;"></div>
        <div style="width: 8px; height: 8px; background: var(--color-brand-orange); border-radius: 50%; animation: bounce 0.6s infinite alternate 0.4s;"></div>
      </div>
      <p>Đang phân tích yêu cầu và chuẩn bị lời khuyên cho bạn...</p>
    </div>
  `;

  try {
    const response = await ai.models.generateContent({
      model: "gemini-3-flash-preview",
      contents: prompt,
      config: {
        systemInstruction: "Bạn là một chuyên gia tư vấn hướng nghiệp tận tâm của CareerNest. Hãy trả lời bằng tiếng Việt, phong cách chuyên nghiệp, khích lệ và giàu thông tin. Sử dụng định dạng HTML đơn giản (h4, p, ul, li) để câu trả lời dễ đọc.",
      },
    });

    contentArea.innerHTML = response.text;
  } catch (error) {
    console.error('AI Assistant Error:', error);
    contentArea.innerHTML = `
      <div style="color: #ef4444; text-align: center;">
        <i data-lucide="alert-circle" style="width: 48px; height: 48px; margin-bottom: 16px;"></i>
        <p>Rất tiếc, đã có lỗi xảy ra khi kết nối với trí tuệ nhân tạo. Vui lòng thử lại sau.</p>
      </div>
    `;
    if (window.lucide) window.lucide.createIcons();
  }
};
