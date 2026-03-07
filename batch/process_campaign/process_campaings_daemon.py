#!/usr/bin/env python

"""
Every n seconds ask the MySQL database what alerts have not yet been sent,
make a request to the PHP minutely cron script, and then ask the MySQL database
what alerts have been sent and log this via syslog. A single run loop will be
used to call the 'cron' PHP script to ensure that only one copy is running
at once.

Only one copy of this script can run at once. The script may be called
periodically by cron to ensure that it is running.

@author: Matthew Kennard <matthew.kennard@oneresult.co.uk>
"""

from daemon.runner import DaemonRunner
import time
import httplib

RUN_LOOP_SLEEP = 10
PIDFILE = '/var/log/process_campaigns_daemon.pid'
PROCESS_HOST = 'alchemis.webapp.stage.ordev.co.uk'
PROCESS_CAMPAIGNS_PATH = '/index.php?cmd=CampaignImport'
PIDFILE_TIMEOUT = 5
STDIN = '/dev/null'
STDOUT = '/var/log/process_campaigns_daemon.log'
STDERR = '/var/log/process_campaigns_err.log'


class ProcessCampaigns(object):

    def __init__(self):
        self.stdin_path = STDIN
        self.stdout_path = STDOUT
        self.stderr_path = STDERR
        self.pidfile_path = PIDFILE
        self.pidfile_timeout = PIDFILE_TIMEOUT

    def process_campaigns(self):
        conn = httplib.HTTPConnection(PROCESS_HOST)
        conn.request('GET', PROCESS_CAMPAIGNS_PATH)
        response = conn.getresponse()

        if response.status != 200:
            print 'Could not process campaigns'

    def run(self):
        print 'Started process_campaigns'
        while True:
            self.process_campaigns()
            time.sleep(RUN_LOOP_SLEEP)


def main():
    runner = DaemonRunner(ProcessCampaigns())
    runner.do_action()


if __name__ == '__main__':
    main()
