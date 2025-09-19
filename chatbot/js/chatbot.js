// استبدال كامل محتوى الملف بما يلي:
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = window.location.origin + '/gestion%20utilisateurs/chatbot/';
    const chatContainer = document.getElementById('chatbot-messages');
    const userInput = document.getElementById('user-input');
    const sendBtn = document.getElementById('send-btn');
    const suggestionsContainer = document.getElementById('suggestions');
    const closeBtn = document.querySelector('.close-btn');

    // إغلاق النافذة
    closeBtn.addEventListener('click', function() {
        window.parent.postMessage({type: 'CLOSE_CHAT'}, '*');
    });

    function sendMessage() {
        const message = userInput.value.trim();
        if (message) {
            addMessage('user', message);
            userInput.value = '';
            
            fetch(baseUrl + 'chatbot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({message: message})
            })
            .then(response => {
                if (!response.ok) throw new Error('Network error');
                return response.json();
            })
            .then(data => {
                addMessage('bot', data.reply);
                if (data.suggestions) {
                    showSuggestions(data.suggestions);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                addMessage('bot', 'عذرًا، حدث خطأ في الاتصال بالخادم');
            });
        }
    }

    function addMessage(sender, text) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `${sender}-message`;
        messageDiv.innerHTML = `<p>${text}</p>`;
        chatContainer.appendChild(messageDiv);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function showSuggestions(suggestions) {
        suggestionsContainer.innerHTML = '';
        suggestions.forEach(suggestion => {
            const btn = document.createElement('button');
            btn.className = 'suggestion-btn';
            btn.textContent = suggestion;
            btn.addEventListener('click', () => {
                userInput.value = suggestion;
                sendMessage();
            });
            suggestionsContainer.appendChild(btn);
        });
    }

    // الأحداث
    sendBtn.addEventListener('click', sendMessage);
    userInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
});