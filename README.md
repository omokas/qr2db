# HMU ID Scanner - Web Client Implementation - Beta

This project implements a HTML5 based QR code reader which sends all scanned codes via PHP to a database. 
Therefore it enables you to use your smartphone, tablet or laptop with webcam to be used as cheap QR Scanner to pass QR data to a database.

## Usage

You do not need to install anything. Just visit the following link and use it:

URL = http://orestis-iot.ddns.net:9000/id_scanner/ 
 username = **test**
 password = **test**
<br/>
>**Note: Camera Stream cannot open on non SSL domain - to bypass temporarily please set the following flag for this domain**

- **Step 1**: enter chrome flags using [chrome://flags](chrome://flags)
- **Step 2**: search for "**unsafely-treat-insecure-origin-as-secure**"
- **Step 3**: **enable** the flag
- **Step 4**: enter into the field the domain name > http://orestis-iot.ddns.net:9000

*Example*: https://tinyurl.com/85x25wzm 

## Add account to DB

In order to add an account to the database, you need to execute the following SQL query

> **INSERT INTO accounts (id, username, password, email) 
> VALUES ('1', 'username_here', 'password_here', 'email@domain.com')**

Passwords must be encrypted with **bcrypt** to be secure in case of SQL injection and database information exposure

**Visit https://bcrypt-generator.com/ to generate your own password**

## Current Compatibility

Chrome - TESTED OK

## Credits

It is written in Javascript and PHP and utilizes the following components:

* QR2MQTT - bytebang - https://github.com/bytebang/qr2mqtt
* GUI: [Bootstrap](https://getbootstrap.com/) and [jQuery](https://jquery.com/)
* QR Code scan engine: [instascan](https://github.com/schmich/instascan)
* Database: [MariaDB](https://mariadb.org/)

## License

HMU Epictetus Team - 2023
MIT License. See LICENSE for details.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
