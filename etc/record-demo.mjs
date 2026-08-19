// Thirty seconds through the helpdesk, as an engineer on it would move: the
// queue, one ticket and its thread, the customer behind it and their machines,
// then the charts the team was measured on.
//
// Paced rather than instant. A recording that jumps between screens as fast as
// the browser can render them reads as a slideshow of unrelated pages; holding
// each one long enough to be read, and scrolling the long ones, is what makes
// it look like someone using the thing.
import { chromium } from 'playwright'
import { mkdirSync, rmSync } from 'fs'

const BASE = process.env.BASE || 'http://localhost:8080'
const OUT = process.env.OUT || 'etc/demo-recording'
const CHROMIUM = process.env.CHROMIUM_PATH || undefined

rmSync(OUT, { recursive: true, force: true })
mkdirSync(OUT, { recursive: true })

const browser = await chromium.launch({ executablePath: CHROMIUM })
const context = await browser.newContext({
  viewport: { width: 1360, height: 850 },
  recordVideo: { dir: OUT, size: { width: 1360, height: 850 } },
  reducedMotion: 'reduce',
})
const page = await context.newPage()

const wait = (ms) => page.waitForTimeout(ms)

/** scroll the way a reader does, rather than jumping to the bottom */
const glide = async (distance, ms) => {
  const steps = Math.max(1, Math.round(ms / 40))
  for (let i = 0; i < steps; i += 1) {
    await page.mouse.wheel(0, distance / steps)
    await wait(40)
  }
}

const go = async (path, hold = 2000) => {
  await page.goto(BASE + path, { waitUntil: 'networkidle' })
  await wait(hold)
}

// the queue, which is where the day starts
await go('/login', 500)
await page.fill('input[name=username]', 'demo')
await wait(300)
await page.fill('input[name=password]', 'demo')
await wait(350)
await page.click('button[type=submit], input[type=submit]')
await page.waitForLoadState('networkidle')
await wait(1600)

await glide(600, 1100)
await wait(500)
await glide(-600, 600)

// one ticket, and the conversation on it
await go('/tickets/3', 1500)
await glide(700, 1200)
await wait(400)

// the customer it came from, and what they run
await go('/companies', 1300)
await go('/companies/2', 1400)
await glide(500, 800)
await wait(400)
await go('/equipment', 1400)
await glide(400, 700)

// and the numbers the team was measured on
await go('/statistics/status-count-per-day', 2000)
await go('/statistics/working-time-by-division', 1900)
await go('/statistics/status-count-to-date', 1800)

// what the back office looked like
await go('/users', 1400)

await context.close()
await browser.close()
console.log('recorded into', OUT)
