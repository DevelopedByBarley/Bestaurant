
type ProgressType = {
  percentage: number
}


const Progress = ({ percentage }: ProgressType) => {
  return (
    <div className="w-full absolute bottom-10 xl:bottom-6 rounded-full h-1 mb-4 bg-gray-700 dark:bg-gray-700">
      <div className="bg-white rounded-full dark:bg-blue-500" style={{ height: '2px', width: `${percentage}%` }}></div>
    </div>
  )
}

export default Progress
