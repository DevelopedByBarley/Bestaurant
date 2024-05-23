
type CursorTypes = {
  cursorPosition: { x: number, y: number},
  percentage: number
}

const Cursor = ({cursorPosition, percentage}: CursorTypes) => {



  return (
    <div
      className="custom-cursor cursor-fade-in bg-gray-900 text-white flex items-center justify-center"
      style={{
        position: 'absolute',
        left: cursorPosition.x,
        top: cursorPosition.y,
        width: '50px',
        height: '50px',
        borderRadius: '50%',
        pointerEvents: 'none', // Ne vegyen részt a kattintáskezelésben
        zIndex: 9999 // Biztosítsd, hogy a kurzor mindig a tartalom felett legyen
      }}
    >
      <svg
        className="size-full"
        width="36"
        height="36"
        viewBox="0 0 36 36"
      >
        <circle cx="18" cy="18" r="16" fill="none" className="stroke-current text-white dark:text-neutral-700" strokeWidth="1"></circle>
        <g className="origin-center -rotate-90 transform">
          <circle cx="18" cy="18" r="16" fill="none" className="stroke-current text-gray-800" strokeWidth="2" strokeDasharray="100" strokeDashoffset={`${percentage}`}>
          </circle>
        </g>
      </svg>
    </div>
  )
}

export default Cursor
