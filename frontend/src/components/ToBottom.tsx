import { RiScrollToBottomFill } from "react-icons/ri";

const ToBottom = () => {
  return (
    <div className="animate-bounce w-16 h-16 absolute bottom-0 left-0 right-0 m-auto transform -translate-x-1/2 -translate-y-1/2 bg-gray-900 flex items-center justify-center rounded-full" >
      <RiScrollToBottomFill size={35} color='white' />
    </div>
  )
}

export default ToBottom
