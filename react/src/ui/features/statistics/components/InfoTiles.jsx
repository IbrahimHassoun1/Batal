import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useSelector } from 'react-redux';
import { faCircleNotch } from '@fortawesome/free-solid-svg-icons';

const InfoTiles = ({icon,title,count}) => {
  const StatisticsState = useSelector((global)=>global.Statistics)
  const loading =StatisticsState.loading

  return (
    <div className='flex-1 bg-white flex flex-col w-full h-full p-1 rounded-md'>
        <div className='icon h-3/5'>
              <FontAwesomeIcon icon={icon} className='ml-[5%] mt-[5%] bg-light_grey p-4 rounded-full'/>
            
        </div>
        <div className='title_count flex-1 flex flex-col pl-[5%]'>
            <div className='title flex-1 text-dark_grey'>{title}</div>
            <div className='title flex-1 text-primary font-bold text-2xl'>{loading?<FontAwesomeIcon icon={faCircleNotch} spin />: count}</div>
        </div>
    </div>
  )
}

export default InfoTiles