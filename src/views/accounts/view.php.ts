import './view.php.scss'

import axios from 'axios'

async function test() {
    const {data} = await axios.get('/api/accounts')
    console.log(data)
}

test()