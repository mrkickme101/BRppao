// script.js

// ============================================================================
// 1) ฟังก์ชันยืนยันการลบ (Confirm Delete)
// ============================================================================

/**
 * confirmDelete - ฟังก์ชันสำหรับยืนยันการลบข้อมูล
 * @param {string} message ข้อความแจ้งเตือน
 * @returns {boolean} true ถ้าต้องการลบ, false ถ้ายกเลิก
 */
function confirmDelete (message) {
  return confirm(message)
}

// ============================================================================
// 2) ฟังก์ชัน Show/Hide Password
// ============================================================================
/**
 * togglePassword - สลับการแสดงรหัสผ่านใน input[type=password]
 * @param {string} toggleBtnId   ID ของปุ่มหรือไอคอนที่ใช้คลิก
 * @param {string} inputFieldId  ID ของ input field ที่เป็นรหัสผ่าน
 */
function togglePassword (toggleBtnId, inputFieldId) {
  const toggleBtn = document.getElementById(toggleBtnId)
  const passwordField = document.getElementById(inputFieldId)

  if (!toggleBtn || !passwordField) return

  toggleBtn.addEventListener('click', () => {
    if (passwordField.type === 'password') {
      passwordField.type = 'text'
      toggleBtn.textContent = 'Hide' // หรือเปลี่ยนเป็นไอคอน/ข้อความตามต้องการ
    } else {
      passwordField.type = 'password'
      toggleBtn.textContent = 'Show' // หรือเปลี่ยนเป็นไอคอน/ข้อความตามต้องการ
    }
  })
}

// ============================================================================
// 3) ฟังก์ชันค้นหา/Filter ตาราง
// ============================================================================
/**
 * filterTable - ฟิลเตอร์ข้อมูลในตาราง โดยใช้ข้อความที่กรอกใน input
 * @param {string} searchInputId  ID ของช่องค้นหา
 * @param {string} tableId        ID ของตารางที่ต้องการค้นหา
 */
function filterTable (searchInputId, tableId) {
  const searchInput = document.getElementById(searchInputId)
  const table = document.getElementById(tableId)

  if (!searchInput || !table) return

  // เมื่อมีการพิมพ์ในช่องค้นหา
  searchInput.addEventListener('keyup', () => {
    const filterText = searchInput.value.toLowerCase()
    const rows = table.getElementsByTagName('tr')

    // เริ่มจาก row แรกที่เป็น head (index=0) ต้องการเริ่มเช็คจาก row ข้อมูล index=1
    for (let i = 1; i < rows.length; i++) {
      const row = rows[i]
      let rowText = row.textContent.toLowerCase()

      // ถ้าไม่พบข้อความ ให้ซ่อน
      if (rowText.indexOf(filterText) > -1) {
        row.style.display = ''
      } else {
        row.style.display = 'none'
      }
    }
  })
}

// ============================================================================
// 4) Modal แบบ Dynamic (ตัวอย่าง)
// ============================================================================
/**
 * showModal - ตัวอย่างการสร้าง Modal แบบไดนามิกผ่าน JavaScript
 * @param {string} modalTitle   หัวข้อ Modal
 * @param {string} modalBody    เนื้อหาใน Modal
 */
function showModal (modalTitle, modalBody) {
  // สร้าง Overlay
  const overlay = document.createElement('div')
  overlay.style.position = 'fixed'
  overlay.style.top = 0
  overlay.style.left = 0
  overlay.style.width = '100%'
  overlay.style.height = '100%'
  overlay.style.backgroundColor = 'rgba(0,0,0,0.5)'
  overlay.style.zIndex = 9999

  // สร้างกล่อง Modal
  const modalBox = document.createElement('div')
  modalBox.style.position = 'absolute'
  modalBox.style.top = '50%'
  modalBox.style.left = '50%'
  modalBox.style.transform = 'translate(-50%, -50%)'
  modalBox.style.width = '400px'
  modalBox.style.backgroundColor = '#fff'
  modalBox.style.padding = '20px'
  modalBox.style.borderRadius = '8px'

  // สร้างหัวข้อ
  const titleElem = document.createElement('h4')
  titleElem.textContent = modalTitle

  // สร้างเนื้อหา
  const bodyElem = document.createElement('p')
  bodyElem.textContent = modalBody

  // ปุ่มปิด
  const closeBtn = document.createElement('button')
  closeBtn.textContent = 'ปิด'
  closeBtn.className = 'btn btn-secondary'
  closeBtn.style.marginTop = '10px'
  closeBtn.addEventListener('click', () => {
    document.body.removeChild(overlay)
  })

  // ใส่องค์ประกอบทั้งหมดเข้ากล่อง
  modalBox.appendChild(titleElem)
  modalBox.appendChild(bodyElem)
  modalBox.appendChild(closeBtn)
  overlay.appendChild(modalBox)

  // แสดง Modal
  document.body.appendChild(overlay)
}

// ============================================================================
// 5) Event Listener ส่วนกลาง เมื่อ DOM โหลดเสร็จ
// ============================================================================
document.addEventListener('DOMContentLoaded', () => {
  console.log('script.js: DOM Loaded!')

  // ตัวอย่างการผูกปุ่มลบทุกปุ่มที่มี class="btn-delete" ให้ใช้ confirmDelete
  const deleteButtons = document.querySelectorAll('.btn-delete')
  deleteButtons.forEach(btn => {
    btn.addEventListener('click', e => {
      const confirmMsg =
        btn.getAttribute('data-confirm') || 'ต้องการลบข้อมูลนี้?'
      if (!confirmDelete(confirmMsg)) {
        e.preventDefault()
      }
    })
  })

  // ตัวอย่างการใช้งาน togglePassword (ถ้ามีปุ่ม/ฟิลด์ในหน้า)
  // togglePassword("togglePasswordBtn", "passwordField");

  // ตัวอย่างการใช้งาน filterTable (ถ้ามี input/ตารางในหน้า)
  // filterTable("searchInput", "myTable");

  // ตัวอย่างการแสดง Modal แบบไดนามิก (อาจเรียกใช้เมื่อกดปุ่มอื่น ๆ)
  // const showModalBtn = document.getElementById("showModalBtn");
  // if (showModalBtn) {
  //   showModalBtn.addEventListener("click", () => {
  //     showModal("แจ้งเตือน", "รายละเอียดในโมดัลแบบไดนามิก");
  //   });
  // }
})
