<div id="floating-chat">
    <button id="chat-button" aria-label="فتح الدردشة">
        <i class="fas fa-comments"></i>
    </button>
    <div id="chat-window" style="display:none;">
        <iframe src="http://localhost/gestion%20utilisateurs/chatbot/chatbot_ui.php" 
                style="width:350px;height:500px;border:none;"></iframe>
    </div>
</div>

<script>
document.getElementById('chat-button').addEventListener('click', function() {
    const chatWindow = document.getElementById('chat-window');
    if (chatWindow.style.display === 'none') {
        chatWindow.style.display = 'block';
        // إعادة تحميل iframe عند كل فتح للتأكد من التحديث
        chatWindow.querySelector('iframe').src = chatWindow.querySelector('iframe').src;
    } else {
        chatWindow.style.display = 'none';
    }
});
</script>

<style>
#floating-chat {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
}

#chat-button {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: #4CAF50;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

#chat-window {
    position: absolute;
    bottom: 70px;
    right: 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    overflow: hidden;
}
</style>

<script>
    // حالة الدردشة
    const chatState = {
        unread: 0,
        isOpen: false,
        init: function() {
            this.loadState();
            this.setupEvents();
            this.setupMessageListener();
        },
        loadState: function() {
            const savedState = localStorage.getItem('floatingChatState');
            if (savedState) {
                const { isOpen } = JSON.parse(savedState);
                this.toggleChat(isOpen, false);
            }
        },
        setupEvents: function() {
            const toggleBtn = document.getElementById('chat-toggle-btn');
            toggleBtn.addEventListener('click', () => {
                this.toggleChat(!this.isOpen);
                if (this.isOpen) this.resetUnread();
            });
        },
        setupMessageListener: function() {
            window.addEventListener('message', (e) => {
                if (e.data.type === 'CHAT_EVENT') {
                    if (e.data.event === 'NEW_MESSAGE' && !this.isOpen) {
                        this.incrementUnread();
                    }
                    if (e.data.event === 'CLOSE_CHAT') {
                        this.toggleChat(false);
                    }
                }
            });
        },
        toggleChat: function(show, save = true) {
            const chatWindow = document.getElementById('chat-window');
            this.isOpen = show;
            
            if (show) {
                chatWindow.classList.add('visible');
                chatWindow.classList.remove('hidden');
            } else {
                chatWindow.classList.remove('visible');
                setTimeout(() => {
                    chatWindow.classList.add('hidden');
                }, 300);
            }
            
            if (save) {
                localStorage.setItem('floatingChatState', 
                    JSON.stringify({ isOpen: show }));
            }
        },
        incrementUnread: function() {
            this.unread++;
            this.updateUnreadBadge();
        },
        resetUnread: function() {
            this.unread = 0;
            this.updateUnreadBadge();
        },
        updateUnreadBadge: function() {
            const badge = document.getElementById('unread-count');
            badge.textContent = this.unread;
            badge.classList.toggle('hidden', this.unread === 0);
        }
    };

    // تهيئة الدردشة عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', () => {
        chatState.init();
        
        // عرض الدردشة تلقائياً لأول زيارة
        if (!localStorage.getItem('chatFirstVisit')) {
            setTimeout(() => {
                chatState.toggleChat(true);
                localStorage.setItem('chatFirstVisit', 'true');
            }, 15000); // بعد 15 ثانية
        }
    });
</script>