/**
 * Main application JavaScript
 */

// Confirm delete actions
document.addEventListener("DOMContentLoaded", () => {
  const deleteButtons = document.querySelectorAll(".delete-confirm")

  deleteButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
      if (!confirm("Are you sure you want to delete this item? This action cannot be undone.")) {
        e.preventDefault()
      }
    })
  })

  // Initialize date pickers if any
  const datePickers = document.querySelectorAll(".datepicker")
  if (datePickers.length > 0) {
    datePickers.forEach((picker) => {
      picker.type = "date"
    })
  }

  // Initialize WYSIWYG editors if any
  const editors = document.querySelectorAll(".wysiwyg-editor")
  if (editors.length > 0) {
    // Simple implementation - can be replaced with a proper WYSIWYG editor
    editors.forEach((editor) => {
      const toolbar = document.createElement("div")
      toolbar.className = "wysiwyg-toolbar flex space-x-2 mb-2 p-2 bg-gray-100 rounded"

      const boldButton = document.createElement("button")
      boldButton.type = "button"
      boldButton.className = "px-2 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50"
      boldButton.innerHTML = "<strong>B</strong>"
      boldButton.addEventListener("click", () => {
        document.execCommand("bold", false, null)
      })

      const italicButton = document.createElement("button")
      italicButton.type = "button"
      italicButton.className = "px-2 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50"
      italicButton.innerHTML = "<em>I</em>"
      italicButton.addEventListener("click", () => {
        document.execCommand("italic", false, null)
      })

      const ulButton = document.createElement("button")
      ulButton.type = "button"
      ulButton.className = "px-2 py-1 bg-white border border-gray-300 rounded hover:bg-gray-50"
      ulButton.innerHTML = "UL"
      ulButton.addEventListener("click", () => {
        document.execCommand("insertUnorderedList", false, null)
      })

      toolbar.appendChild(boldButton)
      toolbar.appendChild(italicButton)
      toolbar.appendChild(ulButton)

      const parent = editor.parentNode
      parent.insertBefore(toolbar, editor)

      editor.style.minHeight = "200px"
      editor.contentEditable = true
      editor.className +=
        " border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"

      // Handle form submission to update the hidden textarea
      const form = editor.closest("form")
      if (form) {
        const hiddenInput = document.createElement("input")
        hiddenInput.type = "hidden"
        hiddenInput.name = editor.getAttribute("data-name") || editor.id
        parent.appendChild(hiddenInput)

        form.addEventListener("submit", () => {
          hiddenInput.value = editor.innerHTML
        })
      }
    })
  }
})

// Handle file input styling
document.addEventListener("DOMContentLoaded", () => {
  const fileInputs = document.querySelectorAll('input[type="file"]')

  fileInputs.forEach((input) => {
    const wrapper = document.createElement("div")
    wrapper.className = "relative"

    const fileNameDisplay = document.createElement("div")
    fileNameDisplay.className = "mt-1 text-sm text-gray-500"
    fileNameDisplay.textContent = "No file selected"

    input.parentNode.insertBefore(wrapper, input)
    wrapper.appendChild(input)
    wrapper.appendChild(fileNameDisplay)

    input.addEventListener("change", () => {
      if (input.files.length > 0) {
        fileNameDisplay.textContent = input.files[0].name
      } else {
        fileNameDisplay.textContent = "No file selected"
      }
    })
  })
})

// Handle responsive tables
document.addEventListener("DOMContentLoaded", () => {
  const tables = document.querySelectorAll("table.responsive-table")

  tables.forEach((table) => {
    const headerCells = table.querySelectorAll("thead th")
    const headerTexts = Array.from(headerCells).map((cell) => cell.textContent.trim())

    const dataCells = table.querySelectorAll("tbody td")

    dataCells.forEach((cell, index) => {
      const headerIndex = index % headerTexts.length
      cell.setAttribute("data-label", headerTexts[headerIndex])
    })
  })
})
