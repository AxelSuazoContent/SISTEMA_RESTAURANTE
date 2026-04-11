const { app, BrowserWindow } = require('electron')
const { spawn } = require('child_process')
const path = require('path')

let mainWindow
let laravelProcess

function getPHPPath() {
  if (app.isPackaged) {
    return path.join(process.resourcesPath, 'php', 'php.exe')
  }
  return path.join(__dirname, 'php', 'php.exe')
}

function getLaravelPath() {
  if (app.isPackaged) {
    return path.join(process.resourcesPath, 'restaurante-laravel')
  }
  return path.join(__dirname, '..', 'restaurante-laravel')
}

function startLaravel() {
  const phpPath = getPHPPath()
  const laravelPath = getLaravelPath()

  laravelProcess = spawn(phpPath, ['artisan', 'serve', '--port=8000'], {
    cwd: laravelPath,
    shell: false
  })

  laravelProcess.stdout.on('data', (data) => {
    console.log(`Laravel: ${data}`)
  })

  laravelProcess.stderr.on('data', (data) => {
    console.error(`Laravel error: ${data}`)
  })
}

function createWindow() {
  mainWindow = new BrowserWindow({
    width: 1280,
    height: 800,
    webPreferences: {
      nodeIntegration: false
    },
    title: 'Sistema Restaurante'
  })

  setTimeout(() => {
    mainWindow.loadURL('http://localhost:8000')
  }, 4000)

  mainWindow.on('closed', () => {
    mainWindow = null
  })
}

app.whenReady().then(() => {
  startLaravel()
  createWindow()
})

app.on('window-all-closed', () => {
  if (laravelProcess) {
    laravelProcess.kill()
  }
  app.quit()
})