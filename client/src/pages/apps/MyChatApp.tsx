import React, { useState, useEffect, useRef } from "react";
import { useLocation } from "wouter";
import MobileHeader from "@/components/mobile/MobileHeader";

interface Contact {
  id: string;
  name: string;
  avatar: string;
  lastMessage: string;
  lastMessageTime: string;
  unreadCount: number;
  isOnline: boolean;
}

interface Message {
  id: string;
  senderId: string;
  text: string;
  timestamp: string;
  isOwn: boolean;
  status: 'sent' | 'delivered' | 'read';
}

const SAMPLE_CONTACTS: Contact[] = [
  {
    id: '1',
    name: 'John Doe',
    avatar: '👨',
    lastMessage: 'Hey, how are you doing?',
    lastMessageTime: '10:30 AM',
    unreadCount: 2,
    isOnline: true
  },
  {
    id: '2',
    name: 'Jane Smith',
    avatar: '👩',
    lastMessage: 'See you tomorrow!',
    lastMessageTime: '9:15 AM',
    unreadCount: 0,
    isOnline: false
  },
  {
    id: '3',
    name: 'Team Group',
    avatar: '👥',
    lastMessage: 'Meeting at 3 PM',
    lastMessageTime: 'Yesterday',
    unreadCount: 5,
    isOnline: true
  },
  {
    id: '4',
    name: 'Mom',
    avatar: '👵',
    lastMessage: 'Don\'t forget to call me',
    lastMessageTime: 'Yesterday',
    unreadCount: 1,
    isOnline: true
  },
];

const SAMPLE_MESSAGES: { [contactId: string]: Message[] } = {
  '1': [
    {
      id: '1',
      senderId: '1',
      text: 'Hey there!',
      timestamp: '10:25 AM',
      isOwn: false,
      status: 'read'
    },
    {
      id: '2',
      senderId: 'me',
      text: 'Hi John! How are you?',
      timestamp: '10:26 AM',
      isOwn: true,
      status: 'read'
    },
    {
      id: '3',
      senderId: '1',
      text: 'I\'m doing great! How about you?',
      timestamp: '10:30 AM',
      isOwn: false,
      status: 'delivered'
    },
  ],
  '2': [
    {
      id: '1',
      senderId: '2',
      text: 'Don\'t forget about tomorrow\'s meeting',
      timestamp: '9:10 AM',
      isOwn: false,
      status: 'read'
    },
    {
      id: '2',
      senderId: 'me',
      text: 'Thanks for reminding me!',
      timestamp: '9:12 AM',
      isOwn: true,
      status: 'read'
    },
    {
      id: '3',
      senderId: '2',
      text: 'See you tomorrow!',
      timestamp: '9:15 AM',
      isOwn: false,
      status: 'read'
    },
  ],
};

export default function MyChatApp() {
  const [, setLocation] = useLocation();
  const [selectedContact, setSelectedContact] = useState<Contact | null>(null);
  const [messages, setMessages] = useState<Message[]>([]);
  const [newMessage, setNewMessage] = useState("");
  const [searchQuery, setSearchQuery] = useState("");
  const messagesEndRef = useRef<HTMLDivElement>(null);

  const filteredContacts = SAMPLE_CONTACTS.filter(contact =>
    contact.name.toLowerCase().includes(searchQuery.toLowerCase())
  );

  useEffect(() => {
    if (selectedContact) {
      setMessages(SAMPLE_MESSAGES[selectedContact.id] || []);
    }
  }, [selectedContact]);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  const handleContactSelect = (contact: Contact) => {
    setSelectedContact(contact);
  };

  const handleSendMessage = () => {
    if (!newMessage.trim() || !selectedContact) return;

    const message: Message = {
      id: Date.now().toString(),
      senderId: 'me',
      text: newMessage,
      timestamp: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
      isOwn: true,
      status: 'sent'
    };

    setMessages(prev => [...prev, message]);
    setNewMessage("");
  };

  const handleBackToHome = () => {
    setLocation('/');
  };

  const handleBackToContacts = () => {
    setSelectedContact(null);
  };

  return (
    <div className="mobile-container">
      <MobileHeader 
        onProfileClick={() => console.log("Profile clicked")}
        onSearch={setSearchQuery}
      />
      
      <div className="app-header bg-success text-white p-3 mb-3">
        <div className="d-flex align-items-center">
          <button 
            className="btn btn-outline-light btn-sm me-3"
            onClick={selectedContact ? handleBackToContacts : handleBackToHome}
          >
            <i className="bi bi-arrow-left"></i>
          </button>
          <div>
            <h5 className="mb-0">
              <i className="bi bi-chat-dots me-2"></i>
              {selectedContact ? selectedContact.name : 'MyChat'}
            </h5>
            <small className="opacity-75">
              {selectedContact 
                ? (selectedContact.isOnline ? 'Online' : 'Last seen recently')
                : 'WhatsApp-style messaging'
              }
            </small>
          </div>
        </div>
      </div>

      {/* Contacts List */}
      {!selectedContact && (
        <div className="px-3">
          <div className="d-flex justify-content-between align-items-center mb-3">
            <h6 className="mb-0">Chats</h6>
            <button className="btn btn-success btn-sm">
              <i className="bi bi-plus-circle me-1"></i>
              New Chat
            </button>
          </div>
          
          <div className="contacts-list">
            {filteredContacts.map((contact) => (
              <div 
                key={contact.id}
                className="contact-item d-flex align-items-center p-3 border-bottom"
                onClick={() => handleContactSelect(contact)}
                style={{ cursor: 'pointer' }}
              >
                <div className="position-relative me-3">
                  <div 
                    className="contact-avatar d-flex align-items-center justify-content-center bg-light rounded-circle"
                    style={{ width: '50px', height: '50px', fontSize: '1.5rem' }}
                  >
                    {contact.avatar}
                  </div>
                  {contact.isOnline && (
                    <span 
                      className="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white"
                      style={{ width: '12px', height: '12px' }}
                    ></span>
                  )}
                </div>
                
                <div className="flex-grow-1">
                  <div className="d-flex justify-content-between align-items-start">
                    <h6 className="mb-1">{contact.name}</h6>
                    <small className="text-muted">{contact.lastMessageTime}</small>
                  </div>
                  <div className="d-flex justify-content-between align-items-center">
                    <p className="mb-0 text-muted small">{contact.lastMessage}</p>
                    {contact.unreadCount > 0 && (
                      <span className="badge bg-success rounded-pill">
                        {contact.unreadCount}
                      </span>
                    )}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* Chat Messages */}
      {selectedContact && (
        <div className="chat-container d-flex flex-column" style={{ height: 'calc(100vh - 200px)' }}>
          <div className="messages-container flex-grow-1 overflow-auto px-3">
            {messages.map((message) => (
              <div 
                key={message.id}
                className={`message-bubble mb-3 ${message.isOwn ? 'own-message' : 'other-message'}`}
              >
                <div 
                  className={`p-3 rounded-3 ${
                    message.isOwn 
                      ? 'bg-primary text-white ms-auto' 
                      : 'bg-light text-dark'
                  }`}
                  style={{ maxWidth: '80%', width: 'fit-content' }}
                >
                  <p className="mb-1">{message.text}</p>
                  <div className="d-flex justify-content-between align-items-center">
                    <small className={message.isOwn ? 'text-white-50' : 'text-muted'}>
                      {message.timestamp}
                    </small>
                    {message.isOwn && (
                      <span className="ms-2">
                        {message.status === 'sent' && <i className="bi bi-check text-white-50"></i>}
                        {message.status === 'delivered' && <i className="bi bi-check-all text-white-50"></i>}
                        {message.status === 'read' && <i className="bi bi-check-all text-info"></i>}
                      </span>
                    )}
                  </div>
                </div>
              </div>
            ))}
            <div ref={messagesEndRef} />
          </div>
          
          {/* Message Input */}
          <div className="message-input-container p-3 border-top bg-white">
            <div className="input-group">
              <button className="btn btn-outline-secondary">
                <i className="bi bi-paperclip"></i>
              </button>
              <input 
                type="text" 
                className="form-control" 
                placeholder="Type a message..."
                value={newMessage}
                onChange={(e) => setNewMessage(e.target.value)}
                onKeyPress={(e) => e.key === 'Enter' && handleSendMessage()}
              />
              <button className="btn btn-outline-secondary">
                <i className="bi bi-emoji-smile"></i>
              </button>
              <button 
                className="btn btn-success"
                onClick={handleSendMessage}
                disabled={!newMessage.trim()}
              >
                <i className="bi bi-send"></i>
              </button>
            </div>
          </div>
        </div>
      )}
      
      <div style={{ height: '80px' }} />
    </div>
  );
}
