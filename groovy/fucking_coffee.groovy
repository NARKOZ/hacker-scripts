@Grab(group='org.hidetake', module='groovy-ssh', version='1.1.8')
@GrabExclude('org.codehaus.groovy:groovy-all')
import org.hidetake.groovy.ssh.Ssh

final def ssh = Ssh.newService()

final def HOST = '10.10.42.42'
final def USER = 'my_username'
final def PASSWORD = '1234'
final def DELAY = 24

ssh.remotes {
    webServer {
        host = HOST
        user = USER
        password = PASSWORD
    }
}

ssh.run {
    session(ssh.remotes.webServer) {
        execute 'sys brew'
        execute "sleep ${DELAY}s"
        execute 'sys pour'
    }
}
