export default {
    path: '/edit-txt-send',
    getComponent(nextState, cb) {
        require.ensure(
            [],
            (require) => {
                cb(null, require('./EditTxtSend').default)
            },
            'edit-txt-send'
        )
    }
}
