@extends('layouts.app')

@section('title', 'Messages - Sports Club Management')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="messages-page">
    <div class="messages-container" data-auth-user-id="{{ auth()->id() }}">
        <!-- Theme Toggle Button -->
        <div class="theme-toggle-wrapper">
            <button class="theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>

        <!-- Header Section -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">
                    <i class="fas fa-comments title-icon"></i>
                    Messages
                </h1>
                <p class="page-subtitle">Communicate with club administrators and members</p>
            </div>
        </div>

        <div class="chat-container">
            <!-- Contacts Panel -->
            <div class="contacts-panel">
                <div class="contacts-header">
                    <h3 class="contacts-title">
                        <i class="fas fa-users"></i>
                        Contacts
                    </h3>
                </div>
                <div class="contacts-list">
                    @foreach($admins as $admin)
                        <div class="contact-item" data-contact-id="{{ $admin->id }}">
                            <div class="contact-avatar">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                            <div class="contact-info">
                                <div class="contact-name">{{ $admin->name }}</div>
                                <div class="contact-role">Administrator</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Chat Panel -->
            <div class="chat-panel">
                <div class="chat-header">
                    <button class="chat-back">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div class="chat-contact-avatar" id="chatContactAvatar">A</div>
                    <div class="chat-contact-info">
                        <div class="chat-contact-name" id="chatContactName">Select a contact</div>
                        <div class="chat-contact-status">
                            <i class="fas fa-circle"></i>
                            <span>Online</span>
                        </div>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessages">
                    <div class="no-messages">
                        <div class="no-messages-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <div class="no-messages-text">No messages yet</div>
                        <div class="no-messages-subtext">Select a contact to start chatting</div>
                    </div>
                </div>

                <div class="chat-input">
                    <form class="input-form" id="messageForm">
                        @csrf
                        <input type="hidden" name="receiver_id" id="receiverId">
                        <textarea 
                            name="body" 
                            class="message-input" 
                            id="messageInput" 
                            placeholder="Type your message..." 
                            rows="1"
                            disabled
                        ></textarea>
                        <button type="submit" class="send-button" id="sendButton" disabled>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #667eea;
    --primary-dark: #764ba2;
    --success-color: #48bb78;
    --warning-color: #ed8936;
    --info-color: #4299e1;
    --danger-color: #f56565;
    --dark-color: #1a202c;
    --light-bg: #f7fafc;
    --text-primary: #1a202c;
    --text-secondary: #718096;
    --border-color: #e2e8f0;
    --shadow-color: rgba(0, 0, 0, 0.1);
    --backdrop-blur: blur(10px);
    --chat-panel-bg: rgba(255, 255, 255, 0.95);
    --message-received-bg: rgba(247, 250, 252, 0.95);
    --chat-input-bg: rgba(247, 250, 252, 0.95);
    --contact-active-bg: rgba(102, 126, 234, 0.1);
    --contact-hover-bg: rgba(102, 126, 234, 0.05);
    --header-gradient-start: rgba(102, 126, 234, 0.05);
    --header-gradient-end: rgba(118, 75, 162, 0.05);
    --card-bg: rgba(255, 255, 255, 0.95);
}

[data-theme="dark"] {
    --primary-color: #667eea;
    --text-primary: #f7fafc;
    --text-secondary: #cbd5e0;
    --border-color: #2d3748;
    --light-bg: #1a202c;
    --shadow-color: rgba(0, 0, 0, 0.3);
    --chat-panel-bg: rgba(45, 55, 72, 0.95);
    --message-received-bg: rgba(45, 55, 72, 0.95);
    --chat-input-bg: rgba(26, 32, 44, 0.95);
    --contact-active-bg: rgba(102, 126, 234, 0.2);
    --contact-hover-bg: rgba(102, 126, 234, 0.1);
    --header-gradient-start: rgba(102, 126, 234, 0.1);
    --header-gradient-end: rgba(118, 75, 162, 0.1);
    --card-bg: rgba(45, 55, 72, 0.95);
}

* {
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--light-bg);
    color: var(--text-primary);
    line-height: 1.6;
    transition: all 0.3s ease;
}

.messages-page {
    background: var(--light-bg);
    min-height: calc(100vh - 200px);
    padding: 2rem 0;
    transition: all 0.3s ease;
}

.messages-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Theme Toggle */
.theme-toggle-wrapper {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}

.theme-toggle {
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.theme-toggle:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Page Header */
.page-header {
    margin-bottom: 2rem;
}

.header-content {
    text-align: center;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    color: var(--text-primary);
}

.title-icon {
    font-size: 2rem;
    color: var(--primary-color);
    background: rgba(102, 126, 234, 0.1);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

[data-theme="dark"] .title-icon {
    background: rgba(102, 126, 234, 0.2);
}

.page-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    font-weight: 400;
    margin: 0;
}

/* Chat Container */
.chat-container {
    display: flex;
    gap: 2rem;
    height: calc(100vh - 280px);
    min-height: 600px;
}

/* Contacts Panel */
.contacts-panel {
    flex: 0 0 320px;
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 30px var(--shadow-color);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
}

.contacts-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: rgba(102, 126, 234, 0.05);
}

[data-theme="dark"] .contacts-header {
    background: rgba(102, 126, 234, 0.1);
}

.contacts-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-primary);
}

.contacts-list {
    flex: 1;
    overflow-y: auto;
}

.contact-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.contact-item:hover {
    background: var(--contact-hover-bg);
}

.contact-item.active {
    background: var(--contact-active-bg);
    border-left: 3px solid var(--primary-color);
}

.contact-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

.contact-info {
    flex: 1;
}

.contact-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.contact-role {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Chat Panel */
.chat-panel {
    flex: 1;
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 30px var(--shadow-color);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: all 0.3s ease;
}

.chat-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: rgba(102, 126, 234, 0.05);
    display: flex;
    align-items: center;
    gap: 1rem;
}

[data-theme="dark"] .chat-header {
    background: rgba(102, 126, 234, 0.1);
}

.chat-back {
    display: none;
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: var(--text-primary);
}

.chat-contact-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}

.chat-contact-info {
    flex: 1;
}

.chat-contact-name {
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.chat-contact-status {
    font-size: 0.8rem;
    color: var(--success-color);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.chat-messages {
    flex: 1;
    padding: 1.5rem;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    background: var(--chat-panel-bg);
}

.message {
    max-width: 70%;
    position: relative;
}

.message.sent {
    align-self: flex-end;
}

.message.received {
    align-self: flex-start;
}

.message-content {
    padding: 1rem;
    border-radius: 12px;
    position: relative;
    box-shadow: 0 2px 10px var(--shadow-color);
}

.message.sent .message-content {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    border-bottom-right-radius: 0;
}

.message.received .message-content {
    background: var(--message-received-bg);
    color: var(--text-primary);
    border-bottom-left-radius: 0;
    border: 1px solid var(--border-color);
}

.message-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.85rem;
}

.message.sent .message-header {
    color: rgba(255, 255, 255, 0.8);
}

.message.received .message-header {
    color: var(--text-secondary);
}

.message-sender {
    font-weight: 600;
}

.message-time {
    font-size: 0.75rem;
}

.message-body {
    line-height: 1.5;
}

.message-status {
    position: absolute;
    bottom: 5px;
    right: 10px;
    font-size: 0.75rem;
}

.message.sent .message-status {
    color: rgba(255, 255, 255, 0.7);
}

.message.received .message-status {
    color: var(--text-secondary);
}

.read-status {
    color: var(--success-color);
    margin-left: 0.25rem;
}

.chat-input {
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    background: var(--chat-input-bg);
}

.input-form {
    display: flex;
    gap: 0.75rem;
}

.message-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 25px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    font-size: 1rem;
    resize: none;
    height: 50px;
    transition: all 0.2s ease;
    background: var(--card-bg);
    color: var(--text-primary);
}

.message-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.send-button {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    transition: all 0.2s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.send-button:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.send-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.no-messages {
    text-align: center;
    padding: 3rem;
    color: var(--text-secondary);
}

.no-messages-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--border-color);
}

.no-messages-text {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.no-messages-subtext {
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .chat-container {
        flex-direction: column;
        height: auto;
    }
    
    .contacts-panel {
        flex: 0 0 auto;
    }
    
    .chat-back {
        display: block;
    }
}

@media (max-width: 768px) {
    .messages-container {
        padding: 0 1rem;
    }
    
    .theme-toggle-wrapper {
        top: 10px;
        right: 10px;
    }
    
    .theme-toggle {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .chat-messages {
        padding: 1rem;
    }
    
    .message {
        max-width: 85%;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .input-form {
        flex-direction: column;
    }
    
    .send-button {
        align-self: flex-end;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Set initial theme
    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);
    
    themeToggle.addEventListener('click', () => {
        const theme = document.documentElement.getAttribute('data-theme');
        const newTheme = theme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
    
    function updateThemeIcon(theme) {
        const icon = themeToggle.querySelector('i');
        icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }

    // Contact selection
    const contactItems = document.querySelectorAll('.contact-item');
    const chatContactName = document.getElementById('chatContactName');
    const chatContactAvatar = document.getElementById('chatContactAvatar');
    const receiverIdInput = document.getElementById('receiverId');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const chatMessages = document.getElementById('chatMessages');
    const messageForm = document.getElementById('messageForm');
    const messagesContainer = document.querySelector('.messages-container');

    let currentContactId = null;
    let pollingInterval = null;
    const authUserId = parseInt(messagesContainer.dataset.authUserId);

    contactItems.forEach(item => {
        item.addEventListener('click', function() {
            // Remove active class from all contacts
            contactItems.forEach(contact => contact.classList.remove('active'));
            
            // Add active class to clicked contact
            this.classList.add('active');
            
            // Update chat header
            const contactName = this.querySelector('.contact-name').textContent;
            const contactInitial = contactName.charAt(0);
            
            chatContactName.textContent = contactName;
            chatContactAvatar.textContent = contactInitial;
            
            // Set receiver ID
            currentContactId = this.getAttribute('data-contact-id');
            receiverIdInput.value = currentContactId;
            
            // Enable message input and send button
            messageInput.disabled = false;
            sendButton.disabled = false;
            
            // Load conversation
            loadConversation(currentContactId);
            
            // Focus on message input
            messageInput.focus();
            
            // Start polling for new messages
            startPolling(currentContactId);
        });
    });

    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight > 150 ? 150 : this.scrollHeight) + 'px';
    });

    // Form submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const messageText = messageInput.value.trim();
        
        if (!messageText) {
            alert('Please enter a message.');
            return;
        }
        
        if (!receiverIdInput.value) {
            alert('Please select a recipient.');
            return;
        }
        
        // Create FormData object manually to avoid issues
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('receiver_id', receiverIdInput.value);
        formData.append('body', messageText);
        
        // Send message via AJAX
        sendMessage(formData);
    });

    // Function to send message
    function sendMessage(formData) {
        // Disable send button during submission
        sendButton.disabled = true;
        const originalContent = sendButton.innerHTML;
        sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        
        fetch("{{ route('messages.store') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => {
            // Log the full response for debugging
            console.log('Response status:', response.status);
            console.log('Response headers:', [...response.headers.entries()]);
            
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(errorData.message || 'Network response was not ok');
                }).catch(() => {
                    throw new Error('Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Success data:', data);
            if (data.status === 'success') {
                // Add message to chat
                addMessageToChat(data.message, true);
                
                // Reset form
                messageForm.reset();
                messageInput.style.height = '50px';
                messageInput.focus();
                
                // Reload conversation to show the new message
                if (currentContactId) {
                    setTimeout(() => {
                        loadConversation(currentContactId);
                    }, 100);
                }
            } else {
                alert('Failed to send message: ' + (data.message || 'Please try again.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message: ' + error.message);
        })
        .finally(() => {
            // Re-enable send button
            sendButton.disabled = false;
            sendButton.innerHTML = originalContent;
        });
    }

    // Function to load conversation
    function loadConversation(userId) {
        // Only load if we have a valid user ID
        if (!userId) return;
        
        // Clear previous timeout if exists
        if (window.conversationTimeout) {
            clearTimeout(window.conversationTimeout);
        }
        
        fetch(`/messages/conversation/${userId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                displayMessages(data.messages);
            } else {
                console.error('Error loading conversation:', data);
                // Stop polling on error
                if (pollingInterval) {
                    clearInterval(pollingInterval);
                    pollingInterval = null;
                }
            }
        })
        .catch(error => {
            console.error('Error loading conversation:', error);
            // Stop polling on network error
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        });
    }

    // Function to display messages
    function displayMessages(messages) {
        if (messages.length === 0) {
            chatMessages.innerHTML = `
                <div class="no-messages">
                    <div class="no-messages-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="no-messages-text">No messages yet</div>
                    <div class="no-messages-subtext">Start a conversation by sending a message</div>
                </div>
            `;
            return;
        }

        chatMessages.innerHTML = '';
        messages.forEach(message => {
            addMessageToChat(message, message.sender_id === authUserId);
        });
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Function to add a message to the chat
    function addMessageToChat(message, isSent) {
        // Remove no-messages placeholder if present
        if (chatMessages.querySelector('.no-messages')) {
            chatMessages.innerHTML = '';
        }
        
        const isRead = message.read_at !== null;
        // Format the time properly
        const messageDate = new Date(message.created_at);
        const messageTime = messageDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        const messageElement = document.createElement('div');
        messageElement.className = `message ${isSent ? 'sent' : 'received'}`;
        messageElement.innerHTML = `
            <div class="message-content">
                <div class="message-header">
                    <div class="message-sender">${isSent ? 'You' : message.sender.name}</div>
                    <div class="message-time">${messageTime}</div>
                </div>
                <div class="message-body">${message.body}</div>
                ${isSent ? `
                    <div class="message-status">
                        <span>${isRead ? 'Read' : 'Sent'}</span>
                        <span class="read-status">
                            <i class="fas fa-check${isRead ? '-double' : ''}" style="${isRead ? 'color: var(--success-color)' : ''}"></i>
                        </span>
                    </div>
                ` : ''}
            </div>
        `;
        
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Function to start polling for new messages
    function startPolling(userId) {
        // Clear any existing interval
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
        
        // Only start polling if we have a valid user ID
        if (userId) {
            // Poll every 3 seconds (more reasonable frequency)
            pollingInterval = setInterval(() => {
                loadConversation(currentContactId);
            }, 3000);
        }
    }

    // Stop polling when leaving the page
    window.addEventListener('beforeunload', () => {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }
    });
    
    // Also stop polling when switching contacts or leaving the messages page
    window.addEventListener('blur', () => {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    });

    // Mobile back button
    const chatBack = document.querySelector('.chat-back');
    if (chatBack) {
        chatBack.addEventListener('click', function() {
            // In a real implementation, this would show the contacts panel on mobile
            alert('In a mobile view, this would show the contacts panel');
        });
    }
});
</script>
@endsection