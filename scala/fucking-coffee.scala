/*******************************************
 *
 * Get Ammonite (http://lihaoyi.github.io/Ammonite/#Ammonite-Shell): 
 *  $ mkdir ~/.ammonite; curl -L -o ~/.ammonite/predef.scala http://git.io/vR04f
 *  $ curl -L -o amm http://git.io/vR08A; chmod +x amm
 *
 * Run script
 *  $ ./amm fucking-coffee.scala
 *
 *******************************************/

import java.net._
import java.io._
import ammonite.ops._
import ammonite.ops.ImplicitWd._

val coffeeMachineIP = "10.10.42.42"
val password = "1234"
val passwordPrompt = "Password: "
val delayBeforeBrew = 17
val delay = 24

if ((%%who "-q").out.string.contains(sys.props("user.name"))) {

  val telnet = new Socket(coffeeMachineIP, 23)
  val out = new PrintWriter(telnet.getOutputStream, true)
  val in = new BufferedReader(new InputStreamReader(telnet.getInputStream))

  println(s"Wait for $delayBeforeBrew seconds")
  Thread.sleep(delayBeforeBrew * 1000);

  if(in.readLine == passwordPrompt){
    out.println(password)
    
    out.println("sys brew")
    Thread.sleep(delay * 1000)
    out.println("sys pour")

  }

  out.close()
  in.close()
  telnet.close()
  
}