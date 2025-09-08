// notification.js - ฟังก์ชันแสดงการแจ้งเตือนสำหรับใช้ทั่วทั้งเว็บไซต์

/**
 * แสดงการแจ้งเตือนแบบ toast notification
 * @param {string} message - ข้อความที่ต้องการแสดง
 * @param {string} type - ประเภทของการแจ้งเตือน ('success', 'error', 'warning', 'info')
 * @param {number} duration - ระยะเวลาแสดงผล (มิลลิวินาที) default: 4000
 */
function showNotification(message, type = 'info', duration = 4000) {
    // ลบ notification เก่าก่อน (ถ้ามี)
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // สร้าง notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        font-size: 14px;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        transform: translateX(350px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        max-width: 320px;
        word-wrap: break-word;
        backdrop-filter: blur(10px);
        user-select: none;
        cursor: pointer;
    `;

    // กำหนดสีและไอคอนตามประเภท
    let bgColor, icon;
    switch (type) {
        case 'success':
            bgColor = 'linear-gradient(135deg, #27ae60, #2ecc71)';
            icon = '✅';
            break;
        case 'error':
            bgColor = 'linear-gradient(135deg, #e74c3c, #c0392b)';
            icon = '❌';
            break;
        case 'warning':
            bgColor = 'linear-gradient(135deg, #f39c12, #e67e22)';
            icon = '⚠️';
            break;
        case 'info':
        default:
            bgColor = 'linear-gradient(135deg, #3498db, #2980b9)';
            icon = 'ℹ️';
    }

    notification.style.background = bgColor;

    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 18px;">${icon}</span>
            <span style="flex: 1;">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" 
                    style="background: none; border: none; color: white; font-size: 18px; cursor: pointer; opacity: 0.8; padding: 0; margin-left: 8px; line-height: 1;"
                    title="ปิด">&times;</button>
        </div>
    `;

    document.body.appendChild(notification);

    // แสดง notification พร้อม animation
    requestAnimationFrame(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    });

    // ซ่อน notification หลังจากเวลาที่กำหนด
    const hideTimer = setTimeout(() => {
        hideNotification(notification);
    }, duration);

    // เพิ่มการคลิกเพื่อปิด
    notification.addEventListener('click', (e) => {
        if (e.target.tagName !== 'BUTTON') {
            clearTimeout(hideTimer);
            hideNotification(notification);
        }
    });

    // หยุด timer เมื่อ hover
    notification.addEventListener('mouseenter', () => {
        clearTimeout(hideTimer);
    });

    // เริ่ม timer ใหม่เมื่อ mouse leave
    notification.addEventListener('mouseleave', () => {
        setTimeout(() => hideNotification(notification), 1000);
    });
}

/**
 * ซ่อน notification พร้อม animation
 * @param {HTMLElement} notification - element ของ notification
 */
function hideNotification(notification) {
    if (notification && notification.parentNode) {
        notification.style.transform = 'translateX(350px)';
        notification.style.opacity = '0';
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 400);
    }
}

/**
 * แสดง notification แบบสำเร็จ
 * @param {string} message - ข้อความ
 * @param {number} duration - ระยะเวลา (มิลลิวินาที)
 */
function showSuccess(message, duration = 4000) {
    showNotification(message, 'success', duration);
}

/**
 * แสดง notification แบบข้อผิดพลาด
 * @param {string} message - ข้อความ
 * @param {number} duration - ระยะเวลา (มิลลิวินาที)
 */
function showError(message, duration = 5000) {
    showNotification(message, 'error', duration);
}

/**
 * แสดง notification แบบเตือน
 * @param {string} message - ข้อความ
 * @param {number} duration - ระยะเวลา (มิลลิวินาที)
 */
function showWarning(message, duration = 4000) {
    showNotification(message, 'warning', duration);
}

/**
 * แสดง notification แบบข้อมูล
 * @param {string} message - ข้อความ
 * @param {number} duration - ระยะเวลา (มิลลิวินาที)
 */
function showInfo(message, duration = 4000) {
    showNotification(message, 'info', duration);
}

/**
 * แสดง notification แบบ loading
 * @param {string} message - ข้อความ
 * @returns {Function} ฟังก์ชันสำหรับปิด loading
 */
function showLoading(message = 'กำลังโหลด...') {
    const notification = document.createElement('div');
    notification.className = 'notification notification-loading';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        font-size: 14px;
        z-index: 10000;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        transform: translateX(350px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        max-width: 320px;
        word-wrap: break-word;
        backdrop-filter: blur(10px);
        background: linear-gradient(135deg, #6c5ce7, #a29bfe);
        user-select: none;
    `;

    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px;">
            <div class="loading-spinner" style="
                width: 18px;
                height: 18px;
                border: 2px solid rgba(255,255,255,0.3);
                border-top: 2px solid white;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            "></div>
            <span style="flex: 1;">${message}</span>
        </div>
    `;

    // เพิ่ม CSS animation สำหรับ spinner
    if (!document.getElementById('notification-spinner-style')) {
        const style = document.createElement('style');
        style.id = 'notification-spinner-style';
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }

    document.body.appendChild(notification);

    // แสดง notification
    requestAnimationFrame(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    });

    // คืนค่าฟังก์ชันสำหรับปิด loading
    return function() {
        hideNotification(notification);
    };
}

/**
 * แสดง notification แบบยืนยัน (confirm)
 * @param {string} message - ข้อความ
 * @param {Function} onConfirm - callback เมื่อกดตกลง
 * @param {Function} onCancel - callback เมื่อกดยกเลิก (optional)
 */
function showConfirm(message, onConfirm, onCancel = null) {
    const notification = document.createElement('div');
    notification.className = 'notification notification-confirm';
    notification.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        padding: 25px;
        border-radius: 15px;
        color: #333;
        font-weight: 500;
        font-size: 16px;
        z-index: 10001;
        box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        max-width: 400px;
        width: 90%;
        background: white;
        opacity: 0;
        transition: all 0.3s ease;
    `;

    // สร้าง backdrop
    const backdrop = document.createElement('div');
    backdrop.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;

    notification.innerHTML = `
        <div style="text-align: center;">
            <div style="font-size: 48px; margin-bottom: 15px;">❓</div>
            <div style="margin-bottom: 25px; line-height: 1.5;">${message}</div>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <button class="confirm-btn" style="
                    background: #27ae60;
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 600;
                    transition: background 0.3s;
                " onmouseover="this.style.background='#219a52'" onmouseout="this.style.background='#27ae60'">
                    ตกลง
                </button>
                <button class="cancel-btn" style="
                    background: #e74c3c;
                    color: white;
                    border: none;
                    padding: 12px 24px;
                    border-radius: 8px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 600;
                    transition: background 0.3s;
                " onmouseover="this.style.background='#c0392b'" onmouseout="this.style.background='#e74c3c'">
                    ยกเลิก
                </button>
            </div>
        </div>
    `;

    document.body.appendChild(backdrop);
    document.body.appendChild(notification);

    // แสดง modal
    requestAnimationFrame(() => {
        backdrop.style.opacity = '1';
        notification.style.opacity = '1';
        notification.style.transform = 'translate(-50%, -50%) scale(1)';
    });

    // Event listeners
    const confirmBtn = notification.querySelector('.confirm-btn');
    const cancelBtn = notification.querySelector('.cancel-btn');

    const cleanup = () => {
        backdrop.style.opacity = '0';
        notification.style.opacity = '0';
        notification.style.transform = 'translate(-50%, -50%) scale(0.8)';
        
        setTimeout(() => {
            backdrop.remove();
            notification.remove();
        }, 300);
    };

    confirmBtn.addEventListener('click', () => {
        cleanup();
        if (onConfirm) onConfirm();
    });

    cancelBtn.addEventListener('click', () => {
        cleanup();
        if (onCancel) onCancel();
    });

    backdrop.addEventListener('click', () => {
        cleanup();
        if (onCancel) onCancel();
    });
}

// Export functions ให้สามารถเรียกใช้จากภายนอกได้
window.showNotification = showNotification;
window.showSuccess = showSuccess;
window.showError = showError;
window.showWarning = showWarning;
window.showInfo = showInfo;
window.showLoading = showLoading;
window.showConfirm = showConfirm;